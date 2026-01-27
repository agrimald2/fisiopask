<template>
  <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="px-6 py-4 bg-gradient-to-r from-indigo-500 to-purple-600">
      <div class="flex items-center gap-3">
        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
            d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
        </svg>
        <h3 class="font-semibold text-white">Filtros</h3>
      </div>
    </div>

    <div class="p-5 space-y-5">
      <!-- Filtro por Doctores -->
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">
          Doctores
          <span v-if="localDoctorIds.length > 0" class="text-indigo-600 ml-1">
            ({{ localDoctorIds.length }})
          </span>
        </label>

        <!-- Selected Tags -->
        <div v-if="localDoctorIds.length > 0" class="flex flex-wrap gap-1.5 mb-2">
          <span 
            v-for="id in localDoctorIds" 
            :key="id"
            class="inline-flex items-center gap-1 px-2 py-1 bg-indigo-100 text-indigo-800 rounded-full text-xs font-medium"
          >
            {{ getDoctorName(id) }}
            <button 
              @click="removeDoctor(id)"
              class="hover:text-indigo-600 transition-colors"
            >
              <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
              </svg>
            </button>
          </span>
        </div>

        <!-- Dropdown Toggle -->
        <div class="relative" ref="doctorDropdownContainer">
          <button
            ref="doctorDropdownButton"
            @click="toggleDoctorDropdown"
            type="button"
            class="w-full px-3 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all bg-white text-left flex items-center justify-between text-sm"
          >
            <span class="text-gray-500">
              {{ localDoctorIds.length > 0 ? 'Agregar más...' : 'Todos los doctores' }}
            </span>
            <svg 
              class="w-4 h-4 text-gray-400 transition-transform" 
              :class="{ 'rotate-180': showDoctorDropdown }"
              fill="none" stroke="currentColor" viewBox="0 0 24 24"
            >
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
            </svg>
          </button>

          <!-- Dropdown Panel -->
          <div 
            v-if="showDoctorDropdown"
            class="absolute z-50 mt-1 w-full bg-white border border-gray-200 rounded-xl shadow-lg max-h-64 overflow-hidden"
          >
            <!-- Search -->
            <div class="p-2 border-b border-gray-100">
              <div class="relative">
                <svg class="absolute left-2.5 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <input
                  ref="doctorSearchInput"
                  v-model="doctorSearch"
                  type="text"
                  placeholder="Buscar doctor..."
                  class="w-full pl-8 pr-3 py-1.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm"
                />
              </div>
            </div>

            <!-- Select All -->
            <div class="px-3 py-2 bg-gray-50 border-b border-gray-100">
              <label class="flex items-center gap-2 cursor-pointer text-sm">
                <input
                  type="checkbox"
                  :checked="areAllDoctorsSelected"
                  :indeterminate.prop="areSomeDoctorsSelected && !areAllDoctorsSelected"
                  @change="toggleAllDoctors"
                  class="w-4 h-4 text-indigo-500 border-gray-300 rounded focus:ring-indigo-500"
                />
                <span class="font-medium text-gray-700">Todos los doctores</span>
                <span class="text-xs text-gray-400 ml-auto">
                  {{ localDoctorIds.length }}/{{ doctors.length }}
                </span>
              </label>
            </div>

            <!-- Doctor List -->
            <div class="overflow-y-auto max-h-40">
              <label
                v-for="doctor in filteredDoctors"
                :key="doctor.id"
                class="flex items-center gap-2 px-3 py-2 cursor-pointer hover:bg-indigo-50 transition-colors text-sm"
              >
                <input
                  type="checkbox"
                  :value="doctor.id"
                  v-model="localDoctorIds"
                  @change="emitDoctorChange"
                  class="w-4 h-4 text-indigo-500 border-gray-300 rounded focus:ring-indigo-500"
                />
                <div 
                  class="w-2 h-2 rounded-full flex-shrink-0"
                  :style="{ backgroundColor: getDoctorColor(doctor.name) }"
                ></div>
                <span class="text-gray-700 truncate">{{ doctor.name }}</span>
              </label>

              <div v-if="filteredDoctors.length === 0" class="p-3 text-center text-gray-500 text-sm">
                No se encontraron doctores
              </div>
            </div>
          </div>
        </div>

        <!-- Overlay to close dropdown -->
        <div 
          v-if="showDoctorDropdown" 
          @click="showDoctorDropdown = false"
          class="fixed inset-0 z-40"
        ></div>
      </div>

      <!-- Filtro por Consultorio -->
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">
          Consultorio
        </label>
        <select
          v-model="localOfficeId"
          @change="emitOfficeChange"
          class="w-full px-3 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all bg-white text-sm"
        >
          <option :value="null">Todos los consultorios</option>
          <option 
            v-for="office in offices" 
            :key="office.id" 
            :value="office.id"
          >
            {{ office.name }}
          </option>
        </select>
      </div>

      <!-- Filtro por Fecha -->
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">
          Ir a fecha
        </label>
        <input
          type="date"
          v-model="localDate"
          @change="emitDateChange"
          class="w-full px-3 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all text-sm"
        />
      </div>

      <!-- Clear Filters -->
      <button
        v-if="hasActiveFilters"
        @click="$emit('clear-filters')"
        class="w-full px-4 py-2.5 text-sm font-medium text-rose-600 bg-rose-50 border border-rose-200 rounded-xl hover:bg-rose-100 transition-all flex items-center justify-center gap-2"
      >
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
        </svg>
        Limpiar filtros
      </button>
    </div>
  </div>
</template>

<script>
import str2hex from "@/ui/str2hex";

export default {
  props: {
    doctors: {
      type: Array,
      required: true,
    },
    offices: {
      type: Array,
      required: true,
    },
    selectedDoctorIds: {
      type: Array,
      default: () => [],
    },
    selectedOfficeId: {
      type: [Number, String],
      default: null,
    },
    selectedDate: {
      type: String,
      default: null,
    },
  },

  emits: ['update:doctorIds', 'update:officeId', 'update:date', 'clear-filters'],

  data() {
    return {
      localDoctorIds: [...this.selectedDoctorIds],
      localOfficeId: this.selectedOfficeId,
      localDate: this.selectedDate,
      showDoctorDropdown: false,
      doctorSearch: '',
    };
  },

  computed: {
    filteredDoctors() {
      if (!this.doctorSearch.trim()) {
        return this.doctors;
      }
      const search = this.doctorSearch.toLowerCase().trim();
      return this.doctors.filter(d => d.name.toLowerCase().includes(search));
    },

    areAllDoctorsSelected() {
      return this.doctors.length > 0 && this.localDoctorIds.length === this.doctors.length;
    },

    areSomeDoctorsSelected() {
      return this.localDoctorIds.length > 0 && this.localDoctorIds.length < this.doctors.length;
    },

    hasActiveFilters() {
      return this.localDoctorIds.length > 0 || this.localOfficeId !== null;
    },
  },

  watch: {
    selectedDoctorIds(newVal) {
      this.localDoctorIds = [...newVal];
    },
    selectedOfficeId(newVal) {
      this.localOfficeId = newVal;
    },
    selectedDate(newVal) {
      this.localDate = newVal;
    },
  },

  methods: {
    getDoctorColor(name) {
      return str2hex(name);
    },

    getDoctorName(id) {
      const doctor = this.doctors.find(d => d.id === id);
      return doctor ? doctor.name : '';
    },

    toggleDoctorDropdown() {
      this.showDoctorDropdown = !this.showDoctorDropdown;
      if (this.showDoctorDropdown) {
        this.doctorSearch = '';
        this.$nextTick(() => {
          if (this.$refs.doctorSearchInput) {
            this.$refs.doctorSearchInput.focus();
          }
        });
      }
    },

    toggleAllDoctors() {
      if (this.areAllDoctorsSelected) {
        this.localDoctorIds = [];
      } else {
        this.localDoctorIds = this.doctors.map(d => d.id);
      }
      this.emitDoctorChange();
    },

    removeDoctor(id) {
      this.localDoctorIds = this.localDoctorIds.filter(did => did !== id);
      this.emitDoctorChange();
    },

    emitDoctorChange() {
      this.$emit('update:doctorIds', [...this.localDoctorIds]);
    },

    emitOfficeChange() {
      this.$emit('update:officeId', this.localOfficeId);
    },

    emitDateChange() {
      this.$emit('update:date', this.localDate);
    },
  },
};
</script>
