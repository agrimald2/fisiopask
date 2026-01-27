<template>
  <app-layout title="Marketing">
    <div class="py-6">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
          <div class="flex items-center gap-4">
            <div class="p-3 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-xl shadow-lg">
              <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                  d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
              </svg>
            </div>
            <div>
              <h1 class="text-2xl font-bold text-gray-800">Marketing</h1>
              <p class="text-gray-500">Exporta clientes con filtros avanzados</p>
            </div>
          </div>
        </div>

        <!-- Main Content -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
          <!-- Filters Panel -->
          <div class="lg:col-span-2 space-y-6">
            <!-- Filtros de Fecha -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
              <div class="px-6 py-4 bg-gradient-to-r from-gray-50 to-white border-b border-gray-100">
                <div class="flex items-center gap-3">
                  <div class="p-2 bg-blue-100 rounded-lg">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                  </div>
                  <h3 class="font-semibold text-gray-800">Filtrar por Última Cita</h3>
                </div>
              </div>
              <div class="p-6">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                  <div>
                    <label class="block text-sm font-medium text-gray-600 mb-2">Desde</label>
                    <input
                      type="date"
                      v-model="filters.last_appointment_from"
                      class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all"
                    />
                  </div>
                  <div>
                    <label class="block text-sm font-medium text-gray-600 mb-2">Hasta</label>
                    <input
                      type="date"
                      v-model="filters.last_appointment_to"
                      class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all"
                    />
                  </div>
                </div>
              </div>
            </div>

            <!-- Filtros de Tarifa -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
              <div class="px-6 py-4 bg-gradient-to-r from-gray-50 to-white border-b border-gray-100">
                <div class="flex items-center gap-3">
                  <div class="p-2 bg-amber-100 rounded-lg">
                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                  </div>
                  <h3 class="font-semibold text-gray-800">Filtrar por Tarifa y Monto</h3>
                </div>
              </div>
              <div class="p-6 space-y-4">
                <!-- Multi-select de Familias/Subfamilias -->
                <div>
                  <label class="block text-sm font-medium text-gray-600 mb-2">
                    Familias y Subfamilias
                    <span v-if="filters.subfamily_ids.length > 0" class="text-emerald-600 ml-2">
                      ({{ filters.subfamily_ids.length }} seleccionadas)
                    </span>
                  </label>
                  
                  <!-- Selected Tags -->
                  <div v-if="filters.subfamily_ids.length > 0" class="flex flex-wrap gap-2 mb-3">
                    <span 
                      v-for="id in filters.subfamily_ids" 
                      :key="id"
                      class="inline-flex items-center gap-1 px-3 py-1.5 bg-amber-100 text-amber-800 rounded-full text-sm font-medium"
                    >
                      {{ getSubfamilyName(id) }}
                      <button 
                        @click="removeSubfamily(id)"
                        class="ml-1 hover:text-amber-600 transition-colors"
                      >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                      </button>
                    </span>
                    <button 
                      @click="filters.subfamily_ids = []"
                      class="text-xs text-gray-500 hover:text-rose-500 transition-colors underline"
                    >
                      Limpiar todo
                    </button>
                  </div>

                  <!-- Dropdown Toggle -->
                  <div class="relative" ref="familyDropdownContainer">
                    <button
                      ref="familyDropdownButton"
                      @click="toggleFamilyDropdown"
                      type="button"
                      class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all bg-white text-left flex items-center justify-between"
                    >
                      <span class="text-gray-500">
                        {{ filters.subfamily_ids.length > 0 ? 'Agregar más subfamilias...' : 'Seleccionar familias o subfamilias...' }}
                      </span>
                      <svg 
                        class="w-5 h-5 text-gray-400 transition-transform" 
                        :class="{ 'rotate-180': showFamilyDropdown }"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24"
                      >
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                      </svg>
                    </button>
                  </div>

                  <!-- Dropdown Content (Teleported to body) -->
                  <teleport to="body">
                    <!-- Overlay to close dropdown -->
                    <div 
                      v-if="showFamilyDropdown" 
                      @click="showFamilyDropdown = false"
                      class="fixed inset-0 z-[9998]"
                    ></div>
                    
                    <!-- Dropdown Panel -->
                    <div 
                      v-if="showFamilyDropdown"
                      @click.stop
                      data-family-dropdown
                      class="fixed z-[9999] bg-white border border-gray-200 rounded-xl shadow-2xl max-h-96 overflow-hidden"
                      :style="dropdownStyles"
                    >
                      <!-- Search Input -->
                      <div class="p-3 border-b border-gray-100 sticky top-0 bg-white z-10">
                        <div class="relative">
                          <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                          </svg>
                          <input
                            ref="familySearchInput"
                            v-model="familySearch"
                            type="text"
                            placeholder="Buscar familia o subfamilia..."
                            @click.stop
                            @focus.stop
                            class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 text-sm"
                          />
                        </div>
                      </div>

                      <!-- Family List -->
                      <div class="overflow-y-auto max-h-64">
                        <template v-for="family in filteredFamilies" :key="family.id">
                          <!-- Family Header -->
                          <div class="sticky top-0 bg-gray-50 px-4 py-2 border-b border-gray-100">
                            <label class="flex items-center gap-3 cursor-pointer group">
                              <input
                                type="checkbox"
                                :checked="isFamilyFullySelected(family)"
                                :indeterminate.prop="isFamilyPartiallySelected(family)"
                                @change="toggleFamily(family)"
                                class="w-4 h-4 text-amber-500 border-gray-300 rounded focus:ring-amber-500"
                              />
                              <span class="font-semibold text-gray-700 group-hover:text-amber-600 transition-colors">
                                {{ family.name }}
                              </span>
                              <span class="text-xs text-gray-400 ml-auto">
                                {{ getSelectedCountForFamily(family) }}/{{ family.subfamilies.length }}
                              </span>
                            </label>
                          </div>
                          
                          <!-- Subfamilies -->
                          <div class="bg-white">
                            <label 
                              v-for="sf in getFilteredSubfamilies(family)" 
                              :key="sf.id"
                              class="flex items-center gap-3 px-4 py-2.5 pl-10 cursor-pointer hover:bg-amber-50 transition-colors border-b border-gray-50 last:border-b-0"
                            >
                              <input
                                type="checkbox"
                                :value="sf.id"
                                v-model="filters.subfamily_ids"
                                class="w-4 h-4 text-amber-500 border-gray-300 rounded focus:ring-amber-500"
                              />
                              <span class="text-gray-600 text-sm">{{ sf.name }}</span>
                            </label>
                          </div>
                        </template>

                        <!-- No results -->
                        <div v-if="filteredFamilies.length === 0" class="p-4 text-center text-gray-500 text-sm">
                          No se encontraron resultados para "{{ familySearch }}"
                        </div>
                      </div>

                      <!-- Footer -->
                      <div class="p-3 border-t border-gray-100 bg-gray-50 flex justify-between items-center">
                        <span class="text-xs text-gray-500">
                          {{ filters.subfamily_ids.length }} subfamilia(s) seleccionada(s)
                        </span>
                        <button
                          @click="showFamilyDropdown = false"
                          class="px-4 py-1.5 bg-emerald-500 text-white text-sm font-medium rounded-lg hover:bg-emerald-600 transition-colors"
                        >
                          Listo
                        </button>
                      </div>
                    </div>
                  </teleport>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                  <div>
                    <label class="block text-sm font-medium text-gray-600 mb-2">Monto Mínimo (S/)</label>
                    <input
                      type="number"
                      v-model="filters.min_amount"
                      placeholder="0.00"
                      min="0"
                      step="0.01"
                      class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all"
                    />
                  </div>
                  <div>
                    <label class="block text-sm font-medium text-gray-600 mb-2">Monto Máximo (S/)</label>
                    <input
                      type="number"
                      v-model="filters.max_amount"
                      placeholder="Sin límite"
                      min="0"
                      step="0.01"
                      class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all"
                    />
                  </div>
                </div>
              </div>
            </div>

            <!-- Filtros Especiales -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
              <div class="px-6 py-4 bg-gradient-to-r from-gray-50 to-white border-b border-gray-100">
                <div class="flex items-center gap-3">
                  <div class="p-2 bg-purple-100 rounded-lg">
                    <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                        d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                    </svg>
                  </div>
                  <h3 class="font-semibold text-gray-800">Filtros Especiales</h3>
                </div>
              </div>
              <div class="p-6 space-y-4">
                <!-- Nunca Asistieron -->
                <label class="flex items-center gap-4 p-4 bg-gradient-to-r from-rose-50 to-orange-50 rounded-xl cursor-pointer hover:from-rose-100 hover:to-orange-100 transition-all border border-rose-100">
                  <input
                    type="checkbox"
                    v-model="filters.never_assisted"
                    class="w-5 h-5 text-rose-500 border-gray-300 rounded focus:ring-rose-500"
                  />
                  <div>
                    <span class="font-medium text-gray-800">Clientes que nunca asistieron</span>
                    <p class="text-sm text-gray-500">Sacaron cita por la web pero nunca asistieron</p>
                  </div>
                </label>

                <!-- Por Licenciado -->
                <div>
                  <label class="block text-sm font-medium text-gray-600 mb-2">Por Licenciado</label>
                  
                  <!-- Doctor Search Select -->
                  <div class="relative" ref="doctorDropdownContainer">
                    <button
                      ref="doctorDropdownButton"
                      @click="toggleDoctorDropdown"
                      type="button"
                      class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all bg-white text-left flex items-center justify-between"
                    >
                      <span :class="filters.doctor_id ? 'text-gray-800' : 'text-gray-500'">
                        {{ getSelectedDoctorName() }}
                      </span>
                      <div class="flex items-center gap-2">
                        <button 
                          v-if="filters.doctor_id"
                          @click.stop="filters.doctor_id = null"
                          class="p-1 hover:bg-gray-100 rounded-full transition-colors"
                        >
                          <svg class="w-4 h-4 text-gray-400 hover:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                          </svg>
                        </button>
                        <svg 
                          class="w-5 h-5 text-gray-400 transition-transform" 
                          :class="{ 'rotate-180': showDoctorDropdown }"
                          fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        >
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                      </div>
                    </button>
                  </div>

                  <!-- Doctor Dropdown Content (Teleported to body) -->
                  <teleport to="body">
                    <!-- Overlay to close dropdown -->
                    <div 
                      v-if="showDoctorDropdown" 
                      @click="showDoctorDropdown = false"
                      class="fixed inset-0 z-[9998]"
                    ></div>
                    
                    <!-- Dropdown Panel -->
                    <div 
                      v-if="showDoctorDropdown"
                      @click.stop
                      data-doctor-dropdown
                      class="fixed z-[9999] bg-white border border-gray-200 rounded-xl shadow-2xl max-h-80 overflow-hidden"
                      :style="doctorDropdownStyles"
                    >
                      <!-- Search Input -->
                      <div class="p-3 border-b border-gray-100 sticky top-0 bg-white z-10">
                        <div class="relative">
                          <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                          </svg>
                          <input
                            ref="doctorSearchInput"
                            v-model="doctorSearch"
                            type="text"
                            placeholder="Buscar licenciado..."
                            @click.stop
                            @focus.stop
                            class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 text-sm"
                          />
                        </div>
                      </div>

                      <!-- Doctor List -->
                      <div class="overflow-y-auto max-h-56">
                        <!-- Opción: Todos los licenciados -->
                        <button
                          @click="selectDoctor(null)"
                          class="w-full px-4 py-3 text-left hover:bg-purple-50 transition-colors flex items-center gap-3 border-b border-gray-100"
                          :class="{ 'bg-purple-50': filters.doctor_id === null }"
                        >
                          <div class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center">
                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                          </div>
                          <span class="font-medium text-gray-700">Todos los licenciados</span>
                          <svg v-if="filters.doctor_id === null" class="w-5 h-5 text-purple-500 ml-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                          </svg>
                        </button>

                        <!-- Lista de doctores -->
                        <button
                          v-for="doctor in filteredDoctors"
                          :key="doctor.id"
                          @click="selectDoctor(doctor.id)"
                          class="w-full px-4 py-3 text-left hover:bg-purple-50 transition-colors flex items-center gap-3 border-b border-gray-50 last:border-b-0"
                          :class="{ 'bg-purple-50': filters.doctor_id === doctor.id }"
                        >
                          <div class="w-8 h-8 rounded-full bg-purple-100 flex items-center justify-center">
                            <span class="text-sm font-semibold text-purple-600">
                              {{ doctor.name.charAt(0).toUpperCase() }}
                            </span>
                          </div>
                          <span class="text-gray-700">{{ doctor.name }}</span>
                          <svg v-if="filters.doctor_id === doctor.id" class="w-5 h-5 text-purple-500 ml-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                          </svg>
                        </button>

                        <!-- No results -->
                        <div v-if="filteredDoctors.length === 0 && doctorSearch.trim()" class="p-4 text-center text-gray-500 text-sm">
                          No se encontraron licenciados para "{{ doctorSearch }}"
                        </div>
                      </div>
                    </div>
                  </teleport>
                </div>
              </div>
            </div>

            <!-- Filtros Demográficos -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
              <div class="px-6 py-4 bg-gradient-to-r from-gray-50 to-white border-b border-gray-100">
                <div class="flex items-center gap-3">
                  <div class="p-2 bg-cyan-100 rounded-lg">
                    <svg class="w-5 h-5 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                  </div>
                  <h3 class="font-semibold text-gray-800">Filtros Demográficos</h3>
                </div>
              </div>
              <div class="p-6 space-y-4">
                <!-- Edad -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                  <div>
                    <label class="block text-sm font-medium text-gray-600 mb-2">Edad Mínima</label>
                    <input
                      type="number"
                      v-model="filters.min_age"
                      placeholder="0"
                      min="0"
                      max="150"
                      class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all"
                    />
                  </div>
                  <div>
                    <label class="block text-sm font-medium text-gray-600 mb-2">Edad Máxima</label>
                    <input
                      type="number"
                      v-model="filters.max_age"
                      placeholder="Sin límite"
                      min="0"
                      max="150"
                      class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all"
                    />
                  </div>
                </div>

                <!-- Sexo -->
                <div>
                  <label class="block text-sm font-medium text-gray-600 mb-2">Sexo</label>
                  <div class="flex gap-4">
                    <label 
                      class="flex-1 flex items-center justify-center gap-2 p-4 rounded-xl border-2 cursor-pointer transition-all"
                      :class="filters.sex === null ? 'border-emerald-500 bg-emerald-50 text-emerald-700' : 'border-gray-200 hover:border-gray-300'"
                    >
                      <input type="radio" v-model="filters.sex" :value="null" class="sr-only" />
                      <span class="font-medium">Todos</span>
                    </label>
                    <label 
                      class="flex-1 flex items-center justify-center gap-2 p-4 rounded-xl border-2 cursor-pointer transition-all"
                      :class="filters.sex === 'M' ? 'border-blue-500 bg-blue-50 text-blue-700' : 'border-gray-200 hover:border-gray-300'"
                    >
                      <input type="radio" v-model="filters.sex" value="M" class="sr-only" />
                      <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 3c1.66 0 3 1.34 3 3s-1.34 3-3 3-3-1.34-3-3 1.34-3 3-3zm0 14.2c-2.5 0-4.71-1.28-6-3.22.03-1.99 4-3.08 6-3.08 1.99 0 5.97 1.09 6 3.08-1.29 1.94-3.5 3.22-6 3.22z"/>
                      </svg>
                      <span class="font-medium">Masculino</span>
                    </label>
                    <label 
                      class="flex-1 flex items-center justify-center gap-2 p-4 rounded-xl border-2 cursor-pointer transition-all"
                      :class="filters.sex === 'F' ? 'border-pink-500 bg-pink-50 text-pink-700' : 'border-gray-200 hover:border-gray-300'"
                    >
                      <input type="radio" v-model="filters.sex" value="F" class="sr-only" />
                      <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 3c1.66 0 3 1.34 3 3s-1.34 3-3 3-3-1.34-3-3 1.34-3 3-3zm0 14.2c-2.5 0-4.71-1.28-6-3.22.03-1.99 4-3.08 6-3.08 1.99 0 5.97 1.09 6 3.08-1.29 1.94-3.5 3.22-6 3.22z"/>
                      </svg>
                      <span class="font-medium">Femenino</span>
                    </label>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Right Sidebar - Fields & Actions -->
          <div class="space-y-6">
            <!-- Selected Fields -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden sticky top-6">
              <div class="px-6 py-4 bg-gradient-to-r from-emerald-500 to-teal-500">
                <div class="flex items-center gap-3">
                  <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                      d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                  </svg>
                  <h3 class="font-semibold text-white">Campos del Excel</h3>
                </div>
                <p class="text-emerald-100 text-sm mt-1">Selecciona los campos a exportar</p>
              </div>
              <div class="p-4 max-h-[400px] overflow-y-auto">
                <div class="space-y-2">
                  <label 
                    v-for="(label, key) in availableFields" 
                    :key="key"
                    class="flex items-center gap-3 p-3 rounded-xl cursor-pointer transition-all"
                    :class="selectedFields.includes(key) ? 'bg-emerald-50 border border-emerald-200' : 'hover:bg-gray-50 border border-transparent'"
                  >
                    <input
                      type="checkbox"
                      :value="key"
                      v-model="selectedFields"
                      class="w-4 h-4 text-emerald-500 border-gray-300 rounded focus:ring-emerald-500"
                    />
                    <span class="text-gray-700" :class="{ 'font-medium': selectedFields.includes(key) }">
                      {{ label }}
                    </span>
                  </label>
                </div>
              </div>

              <!-- Quick Select -->
              <div class="px-4 py-3 bg-gray-50 border-t border-gray-100 flex gap-2">
                <button
                  @click="selectAllFields"
                  class="flex-1 px-3 py-2 text-sm font-medium text-gray-600 bg-white border border-gray-200 rounded-lg hover:bg-gray-50 transition-all"
                >
                  Todos
                </button>
                <button
                  @click="selectBasicFields"
                  class="flex-1 px-3 py-2 text-sm font-medium text-gray-600 bg-white border border-gray-200 rounded-lg hover:bg-gray-50 transition-all"
                >
                  Básicos
                </button>
                <button
                  @click="clearFields"
                  class="flex-1 px-3 py-2 text-sm font-medium text-gray-600 bg-white border border-gray-200 rounded-lg hover:bg-gray-50 transition-all"
                >
                  Limpiar
                </button>
              </div>

              <!-- Preview & Export -->
              <div class="p-4 space-y-3 border-t border-gray-100">
                <!-- Info: No filters message -->
                <div v-if="!hasActiveFilters" class="p-4 rounded-xl bg-blue-50 border border-blue-200">
                  <div class="flex items-start gap-3">
                    <svg class="w-5 h-5 text-blue-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div>
                      <p class="text-sm font-medium text-blue-700">Sin filtros activos</p>
                      <p class="text-xs text-blue-600 mt-1">Se exportarán todos los pacientes registrados en el sistema.</p>
                    </div>
                  </div>
                </div>

                <!-- Info: Active filters count -->
                <div v-else class="p-4 rounded-xl bg-amber-50 border border-amber-200">
                  <div class="flex items-start gap-3">
                    <svg class="w-5 h-5 text-amber-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                    </svg>
                    <div>
                      <p class="text-sm font-medium text-amber-700">{{ activeFiltersCount }} filtro(s) activo(s)</p>
                      <p class="text-xs text-amber-600 mt-1">Solo se exportarán pacientes que coincidan con los filtros.</p>
                    </div>
                  </div>
                </div>

                <!-- Preview Result -->
                <div v-if="previewCount !== null" class="p-4 rounded-xl" :class="previewCount > 0 ? 'bg-emerald-50 border border-emerald-200' : 'bg-rose-50 border border-rose-200'">
                  <div class="flex items-center gap-2">
                    <svg v-if="previewCount > 0" class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <svg v-else class="w-5 h-5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    <span class="font-semibold" :class="previewCount > 0 ? 'text-emerald-700' : 'text-rose-700'">
                      {{ previewCount.toLocaleString() }} pacientes encontrados
                    </span>
                  </div>
                </div>

                <!-- Clear All Filters Button -->
                <button
                  v-if="hasActiveFilters"
                  @click="clearFilters"
                  class="w-full px-4 py-3 font-medium text-rose-600 bg-rose-50 border-2 border-rose-200 rounded-xl hover:bg-rose-100 hover:border-rose-300 transition-all flex items-center justify-center gap-2"
                >
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                  </svg>
                  Borrar todos los filtros
                </button>

                <!-- Preview Button -->
                <button
                  @click="getPreview"
                  :disabled="loadingPreview || selectedFields.length === 0"
                  class="w-full px-4 py-3 font-medium text-gray-700 bg-white border-2 border-gray-200 rounded-xl hover:border-emerald-500 hover:text-emerald-600 transition-all disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2"
                >
                  <svg v-if="loadingPreview" class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                  </svg>
                  <svg v-else class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                  </svg>
                  Vista Previa
                </button>

                <!-- Export Button -->
                <button
                  @click="exportCsv"
                  :disabled="loadingExport || selectedFields.length === 0"
                  class="w-full px-4 py-4 font-semibold text-white bg-gradient-to-r from-emerald-500 to-teal-500 rounded-xl hover:from-emerald-600 hover:to-teal-600 transition-all disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2 shadow-lg shadow-emerald-500/30"
                >
                  <svg v-if="loadingExport" class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                  </svg>
                  <svg v-else class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                  </svg>
                  {{ hasActiveFilters ? 'Exportar Filtrados (CSV)' : 'Exportar Todos (CSV)' }}
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </app-layout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout.vue";
import axios from "axios";

export default {
  props: {
    doctors: Array,
    families: Array,
    availableFields: Object,
  },

  components: {
    AppLayout,
  },

  mounted() {
    window.addEventListener('scroll', this.handleScroll, true);
    window.addEventListener('resize', this.handleResize);
  },

  beforeUnmount() {
    window.removeEventListener('scroll', this.handleScroll, true);
    window.removeEventListener('resize', this.handleResize);
  },

  data() {
    return {
      filters: {
        last_appointment_from: null,
        last_appointment_to: null,
        subfamily_ids: [],
        min_amount: null,
        max_amount: null,
        never_assisted: false,
        doctor_id: null,
        min_age: null,
        max_age: null,
        sex: null,
      },
      selectedFields: ['fullname', 'dni', 'phone', 'email'],
      loadingPreview: false,
      loadingExport: false,
      previewCount: null,
      showFamilyDropdown: false,
      familySearch: '',
      dropdownPosition: {
        top: 0,
        left: 0,
        width: 0,
      },
      showDoctorDropdown: false,
      doctorSearch: '',
      doctorDropdownPosition: {
        top: 0,
        left: 0,
        width: 0,
      },
    };
  },

  computed: {
    hasActiveFilters() {
      return (
        this.filters.last_appointment_from !== null ||
        this.filters.last_appointment_to !== null ||
        this.filters.subfamily_ids.length > 0 ||
        (this.filters.min_amount !== null && this.filters.min_amount !== '') ||
        (this.filters.max_amount !== null && this.filters.max_amount !== '') ||
        this.filters.never_assisted === true ||
        this.filters.doctor_id !== null ||
        (this.filters.min_age !== null && this.filters.min_age !== '') ||
        (this.filters.max_age !== null && this.filters.max_age !== '') ||
        this.filters.sex !== null
      );
    },

    activeFiltersCount() {
      let count = 0;
      if (this.filters.last_appointment_from || this.filters.last_appointment_to) count++;
      if (this.filters.subfamily_ids.length > 0) count++;
      if (this.filters.min_amount || this.filters.max_amount) count++;
      if (this.filters.never_assisted) count++;
      if (this.filters.doctor_id) count++;
      if (this.filters.min_age || this.filters.max_age) count++;
      if (this.filters.sex) count++;
      return count;
    },

    filteredFamilies() {
      if (!this.familySearch.trim()) {
        return this.families;
      }
      const search = this.familySearch.toLowerCase().trim();
      return this.families
        .map(family => ({
          ...family,
          subfamilies: family.subfamilies.filter(sf => 
            sf.name.toLowerCase().includes(search)
          ),
          matchesFamily: family.name.toLowerCase().includes(search),
        }))
        .filter(family => family.matchesFamily || family.subfamilies.length > 0);
    },

    allSubfamilies() {
      const all = [];
      this.families.forEach(family => {
        family.subfamilies.forEach(sf => {
          all.push({ ...sf, familyName: family.name });
        });
      });
      return all;
    },

    dropdownStyles() {
      return {
        top: `${this.dropdownPosition.top}px`,
        left: `${this.dropdownPosition.left}px`,
        width: `${this.dropdownPosition.width}px`,
      };
    },

    filteredDoctors() {
      if (!this.doctorSearch.trim()) {
        return this.doctors;
      }
      const search = this.doctorSearch.toLowerCase().trim();
      return this.doctors.filter(doctor => 
        doctor.name.toLowerCase().includes(search)
      );
    },

    doctorDropdownStyles() {
      return {
        top: `${this.doctorDropdownPosition.top}px`,
        left: `${this.doctorDropdownPosition.left}px`,
        width: `${this.doctorDropdownPosition.width}px`,
      };
    },
  },

  methods: {
    selectAllFields() {
      this.selectedFields = Object.keys(this.availableFields);
    },

    selectBasicFields() {
      this.selectedFields = ['fullname', 'dni', 'phone', 'email', 'sex', 'age'];
    },

    clearFields() {
      this.selectedFields = [];
    },

    clearFilters() {
      this.filters = {
        last_appointment_from: null,
        last_appointment_to: null,
        subfamily_ids: [],
        min_amount: null,
        max_amount: null,
        never_assisted: false,
        doctor_id: null,
        min_age: null,
        max_age: null,
        sex: null,
      };
      this.previewCount = null;
      this.familySearch = '';
      this.doctorSearch = '';
    },

    // Multi-select methods
    getSubfamilyName(id) {
      const sf = this.allSubfamilies.find(s => s.id === id);
      return sf ? sf.name : '';
    },

    removeSubfamily(id) {
      this.filters.subfamily_ids = this.filters.subfamily_ids.filter(sid => sid !== id);
    },

    isFamilyFullySelected(family) {
      return family.subfamilies.every(sf => this.filters.subfamily_ids.includes(sf.id));
    },

    isFamilyPartiallySelected(family) {
      const selectedCount = family.subfamilies.filter(sf => this.filters.subfamily_ids.includes(sf.id)).length;
      return selectedCount > 0 && selectedCount < family.subfamilies.length;
    },

    getSelectedCountForFamily(family) {
      return family.subfamilies.filter(sf => this.filters.subfamily_ids.includes(sf.id)).length;
    },

    toggleFamily(family) {
      const allIds = family.subfamilies.map(sf => sf.id);
      const allSelected = this.isFamilyFullySelected(family);

      if (allSelected) {
        this.filters.subfamily_ids = this.filters.subfamily_ids.filter(id => !allIds.includes(id));
      } else {
        const newIds = allIds.filter(id => !this.filters.subfamily_ids.includes(id));
        this.filters.subfamily_ids = [...this.filters.subfamily_ids, ...newIds];
      }
    },

    getFilteredSubfamilies(family) {
      if (!this.familySearch.trim()) {
        return family.subfamilies;
      }
      const search = this.familySearch.toLowerCase().trim();
      if (family.name.toLowerCase().includes(search)) {
        return family.subfamilies;
      }
      return family.subfamilies.filter(sf => sf.name.toLowerCase().includes(search));
    },

    toggleFamilyDropdown() {
      this.showFamilyDropdown = !this.showFamilyDropdown;
      if (this.showFamilyDropdown) {
        this.$nextTick(() => {
          this.updateDropdownPosition();
          if (this.$refs.familySearchInput) {
            this.$refs.familySearchInput.focus();
          }
        });
      }
    },

    updateDropdownPosition() {
      const button = this.$refs.familyDropdownButton;
      if (button) {
        const rect = button.getBoundingClientRect();
        this.dropdownPosition = {
          top: rect.bottom + 8,
          left: rect.left,
          width: rect.width,
        };
      }
    },

    handleScroll(event) {
      // Verificar dropdown de familias
      if (this.showFamilyDropdown) {
        const familyDropdown = document.querySelector('[data-family-dropdown]');
        if (familyDropdown && familyDropdown.contains(event.target)) {
          return;
        }
        this.showFamilyDropdown = false;
      }
      // Verificar dropdown de doctores
      if (this.showDoctorDropdown) {
        const doctorDropdown = document.querySelector('[data-doctor-dropdown]');
        if (doctorDropdown && doctorDropdown.contains(event.target)) {
          return;
        }
        this.showDoctorDropdown = false;
      }
    },

    handleResize() {
      if (this.showFamilyDropdown) {
        this.showFamilyDropdown = false;
      }
      if (this.showDoctorDropdown) {
        this.showDoctorDropdown = false;
      }
    },

    // Doctor dropdown methods
    toggleDoctorDropdown() {
      this.showDoctorDropdown = !this.showDoctorDropdown;
      if (this.showDoctorDropdown) {
        this.doctorSearch = '';
        this.$nextTick(() => {
          this.updateDoctorDropdownPosition();
          if (this.$refs.doctorSearchInput) {
            this.$refs.doctorSearchInput.focus();
          }
        });
      }
    },

    updateDoctorDropdownPosition() {
      const button = this.$refs.doctorDropdownButton;
      if (button) {
        const rect = button.getBoundingClientRect();
        this.doctorDropdownPosition = {
          top: rect.bottom + 8,
          left: rect.left,
          width: rect.width,
        };
      }
    },

    selectDoctor(doctorId) {
      this.filters.doctor_id = doctorId;
      this.showDoctorDropdown = false;
      this.doctorSearch = '';
    },

    getSelectedDoctorName() {
      if (this.filters.doctor_id === null) {
        return 'Todos los licenciados';
      }
      const doctor = this.doctors.find(d => d.id === this.filters.doctor_id);
      return doctor ? doctor.name : 'Todos los licenciados';
    },

    async getPreview() {
      if (this.selectedFields.length === 0) {
        return;
      }

      this.loadingPreview = true;
      this.previewCount = null;

      try {
        const response = await axios.post(route('marketing.preview'), {
          ...this.filters,
          selected_fields: this.selectedFields,
        });
        this.previewCount = response.data.count;
      } catch (error) {
        console.error('Error al obtener vista previa:', error);
        this.previewCount = 0;
      } finally {
        this.loadingPreview = false;
      }
    },

    async exportCsv() {
      if (this.selectedFields.length === 0) {
        return;
      }

      this.loadingExport = true;

      try {
        const response = await axios.post(route('marketing.export'), {
          ...this.filters,
          selected_fields: this.selectedFields,
        }, {
          responseType: 'blob',
        });

        const url = window.URL.createObjectURL(new Blob([response.data], { type: 'text/csv;charset=utf-8;' }));
        const link = document.createElement('a');
        link.href = url;
        
        const contentDisposition = response.headers['content-disposition'];
        let filename = 'marketing-pacientes.csv';
        if (contentDisposition) {
          const filenameMatch = contentDisposition.match(/filename="?([^"]+)"?/);
          if (filenameMatch) {
            filename = filenameMatch[1].replace(/"/g, '');
          }
        }
        
        link.setAttribute('download', filename);
        document.body.appendChild(link);
        link.click();
        link.remove();
        window.URL.revokeObjectURL(url);
      } catch (error) {
        console.error('Error al exportar:', error);
        alert('Error al exportar el archivo. Por favor, intente nuevamente.');
      } finally {
        this.loadingExport = false;
      }
    },
  },
};
</script>
