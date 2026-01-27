<template>
  <teleport to="body">
    <!-- Overlay -->
    <transition name="fade">
      <div 
        v-if="show" 
        class="fixed inset-0 z-50 flex items-center justify-center p-4"
      >
        <div 
          class="absolute inset-0 bg-black/60 backdrop-blur-sm"
          @click="closeWizard"
        ></div>

        <!-- Modal -->
        <transition name="scale">
          <div 
            v-if="show"
            class="relative bg-white rounded-3xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-hidden"
          >
            <!-- Header -->
            <div class="bg-gradient-to-r from-[#0cb8b6] to-[#0a9f9d] px-8 py-6 text-white">
              <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                  <div class="p-3 bg-white/20 rounded-xl">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                  </div>
                  <div>
                    <h2 class="text-xl font-bold">Exportar Disponibilidad</h2>
                    <p class="text-white/80 text-sm">Genera un PDF para tus clientes</p>
                  </div>
                </div>
                <button 
                  @click="closeWizard"
                  class="p-2 rounded-lg hover:bg-white/20 transition-colors"
                >
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                  </svg>
                </button>
              </div>

              <!-- Steps Indicator -->
              <div class="flex items-center justify-center mt-6 gap-2">
                <template v-for="s in 3" :key="s">
                  <div 
                    class="flex items-center gap-2"
                    :class="s <= step ? 'text-white' : 'text-white/40'"
                  >
                    <div 
                      class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-sm transition-all"
                      :class="s === step ? 'bg-white text-[#0cb8b6] scale-110' : s < step ? 'bg-white/30' : 'bg-white/10'"
                    >
                      <svg v-if="s < step" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                      </svg>
                      <span v-else>{{ s }}</span>
                    </div>
                    <span class="text-sm font-medium hidden sm:inline">{{ stepLabels[s - 1] }}</span>
                  </div>
                  <div 
                    v-if="s < 3" 
                    class="w-8 h-0.5 rounded-full"
                    :class="s < step ? 'bg-white/50' : 'bg-white/20'"
                  ></div>
                </template>
              </div>
            </div>

            <!-- Content -->
            <div class="p-8 overflow-y-auto max-h-[50vh]">
              <!-- Step 1: Select Doctors -->
              <div v-if="step === 1">
                <h3 class="text-lg font-semibold text-gray-800 mb-2">Selecciona los Licenciados</h3>
                <p class="text-gray-500 text-sm mb-6">Elige los profesionales cuya disponibilidad deseas exportar</p>

                <!-- Search -->
                <div class="relative mb-4">
                  <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                  </svg>
                  <input
                    v-model="doctorSearch"
                    type="text"
                    placeholder="Buscar licenciado..."
                    class="w-full pl-10 pr-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-[#0cb8b6] focus:border-[#0cb8b6] transition-all"
                  />
                </div>

                <!-- Select All -->
                <div class="flex items-center justify-between mb-4 p-3 bg-gray-50 rounded-xl">
                  <label class="flex items-center gap-3 cursor-pointer">
                    <input
                      type="checkbox"
                      :checked="allDoctorsSelected"
                      :indeterminate.prop="someDoctorsSelected && !allDoctorsSelected"
                      @change="toggleAllDoctors"
                      class="w-5 h-5 text-[#0cb8b6] border-gray-300 rounded focus:ring-[#0cb8b6]"
                    />
                    <span class="font-medium text-gray-700">Seleccionar todos</span>
                  </label>
                  <span class="text-sm text-[#0cb8b6] font-semibold">
                    {{ selectedDoctorIds.length }} seleccionado(s)
                  </span>
                </div>

                <!-- Doctor List -->
                <div class="space-y-2 max-h-48 overflow-y-auto">
                  <label
                    v-for="doctor in filteredDoctors"
                    :key="doctor.id"
                    class="flex items-center gap-3 p-3 rounded-xl border-2 cursor-pointer transition-all"
                    :class="selectedDoctorIds.includes(doctor.id) 
                      ? 'border-[#0cb8b6] bg-[#e8f7f7]' 
                      : 'border-gray-100 hover:border-gray-200'"
                  >
                    <input
                      type="checkbox"
                      :value="doctor.id"
                      v-model="selectedDoctorIds"
                      class="w-5 h-5 text-[#0cb8b6] border-gray-300 rounded focus:ring-[#0cb8b6]"
                    />
                    <div 
                      class="w-10 h-10 rounded-full flex items-center justify-center text-white font-bold"
                      :style="{ backgroundColor: getDoctorColor(doctor.name) }"
                    >
                      {{ doctor.name.charAt(0).toUpperCase() }}
                    </div>
                    <span class="font-medium text-gray-700">{{ doctor.name }}</span>
                  </label>
                </div>
              </div>

              <!-- Step 2: Select Dates -->
              <div v-if="step === 2">
                <h3 class="text-lg font-semibold text-gray-800 mb-2">Selecciona el Rango de Fechas</h3>
                <p class="text-gray-500 text-sm mb-6">Define el período para consultar la disponibilidad</p>

                <!-- Quick Presets -->
                <div class="grid grid-cols-2 gap-3 mb-6">
                  <button
                    v-for="preset in datePresets"
                    :key="preset.label"
                    @click="applyPreset(preset)"
                    class="p-3 rounded-xl border-2 text-left transition-all hover:border-[#0cb8b6] hover:bg-[#e8f7f7]"
                    :class="isPresetActive(preset) ? 'border-[#0cb8b6] bg-[#e8f7f7]' : 'border-gray-100'"
                  >
                    <div class="font-medium text-gray-700">{{ preset.label }}</div>
                    <div class="text-xs text-gray-500">{{ preset.description }}</div>
                  </button>
                </div>

                <!-- Date Inputs -->
                <div class="grid grid-cols-2 gap-4">
                  <div>
                    <label class="block text-sm font-medium text-gray-600 mb-2">Fecha Inicio</label>
                    <input
                      type="date"
                      v-model="dateFrom"
                      :min="today"
                      class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-[#0cb8b6] focus:border-[#0cb8b6] transition-all"
                    />
                  </div>
                  <div>
                    <label class="block text-sm font-medium text-gray-600 mb-2">Fecha Fin</label>
                    <input
                      type="date"
                      v-model="dateTo"
                      :min="dateFrom || today"
                      class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-[#0cb8b6] focus:border-[#0cb8b6] transition-all"
                    />
                  </div>
                </div>

                <!-- Days Summary -->
                <div v-if="dateFrom && dateTo" class="mt-4 p-4 bg-[#e8f7f7] rounded-xl border border-[#0cb8b6]/30">
                  <div class="flex items-center gap-2 text-[#0a9f9d]">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <span class="font-medium">{{ daysCount }} día(s) seleccionado(s)</span>
                  </div>
                </div>
              </div>

              <!-- Step 3: Preview & Export -->
              <div v-if="step === 3">
                <h3 class="text-lg font-semibold text-gray-800 mb-2">Vista Previa y Exportar</h3>
                <p class="text-gray-500 text-sm mb-6">Revisa la selección antes de generar el PDF</p>

                <!-- Summary Cards -->
                <div class="grid grid-cols-2 gap-4 mb-6">
                  <div class="p-4 bg-gradient-to-br from-[#0cb8b6]/10 to-[#0cb8b6]/5 rounded-xl border border-[#0cb8b6]/20">
                    <div class="text-3xl font-bold text-[#0cb8b6]">{{ selectedDoctorIds.length }}</div>
                    <div class="text-sm text-gray-600">Licenciado(s)</div>
                  </div>
                  <div class="p-4 bg-gradient-to-br from-[#0cb8b6]/10 to-[#0cb8b6]/5 rounded-xl border border-[#0cb8b6]/20">
                    <div class="text-3xl font-bold text-[#0cb8b6]">{{ daysCount }}</div>
                    <div class="text-sm text-gray-600">Día(s)</div>
                  </div>
                </div>

                <!-- Selected Doctors -->
                <div class="mb-4">
                  <div class="text-sm font-medium text-gray-600 mb-2">Licenciados seleccionados:</div>
                  <div class="flex flex-wrap gap-2">
                    <span 
                      v-for="id in selectedDoctorIds" 
                      :key="id"
                      class="px-3 py-1.5 bg-[#e8f7f7] text-[#0a9f9d] rounded-full text-sm font-medium"
                    >
                      {{ getDoctorName(id) }}
                    </span>
                  </div>
                </div>

                <!-- Date Range -->
                <div class="p-4 bg-gray-50 rounded-xl">
                  <div class="text-sm font-medium text-gray-600 mb-1">Período:</div>
                  <div class="font-semibold text-gray-800">
                    {{ formatDate(dateFrom) }} - {{ formatDate(dateTo) }}
                  </div>
                </div>

                <!-- Loading Preview -->
                <div v-if="loadingPreview" class="mt-6 flex items-center justify-center py-8">
                  <svg class="w-8 h-8 text-[#0cb8b6] animate-spin" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                  </svg>
                </div>

                <!-- Preview Result -->
                <div v-else-if="previewData" class="mt-6">
                  <div class="p-4 rounded-xl" :class="totalAvailableSlots > 0 ? 'bg-emerald-50 border border-emerald-200' : 'bg-amber-50 border border-amber-200'">
                    <div class="flex items-center gap-2">
                      <svg v-if="totalAvailableSlots > 0" class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                      </svg>
                      <svg v-else class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                      </svg>
                      <span class="font-semibold" :class="totalAvailableSlots > 0 ? 'text-emerald-700' : 'text-amber-700'">
                        {{ totalAvailableSlots }} horario(s) disponible(s) encontrado(s)
                      </span>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Footer -->
            <div class="px-8 py-5 bg-gray-50 border-t border-gray-100 flex items-center justify-between">
              <button
                v-if="step > 1"
                @click="previousStep"
                class="px-5 py-2.5 text-gray-600 font-medium rounded-xl hover:bg-gray-100 transition-all flex items-center gap-2"
              >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                Anterior
              </button>
              <div v-else></div>

              <button
                v-if="step < 3"
                @click="nextStep"
                :disabled="!canProceed"
                class="px-6 py-2.5 bg-gradient-to-r from-[#0cb8b6] to-[#0a9f9d] text-white font-semibold rounded-xl hover:shadow-lg hover:shadow-[#0cb8b6]/30 transition-all disabled:opacity-50 disabled:cursor-not-allowed flex items-center gap-2"
              >
                Siguiente
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
              </button>

              <button
                v-else
                @click="exportPdf"
                :disabled="loadingExport"
                class="px-6 py-2.5 bg-gradient-to-r from-[#0cb8b6] to-[#0a9f9d] text-white font-semibold rounded-xl hover:shadow-lg hover:shadow-[#0cb8b6]/30 transition-all disabled:opacity-50 flex items-center gap-2"
              >
                <svg v-if="loadingExport" class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                  <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <svg v-else class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                {{ loadingExport ? 'Generando...' : 'Descargar PDF' }}
              </button>
            </div>
          </div>
        </transition>
      </div>
    </transition>
  </teleport>
</template>

<script>
import axios from "axios";
import str2hex from "@/ui/str2hex";
import dates from "@/ui/dates";

export default {
  props: {
    show: {
      type: Boolean,
      default: false,
    },
    doctors: {
      type: Array,
      required: true,
    },
  },

  emits: ['close'],

  data() {
    const today = dates.todayString();
    
    return {
      step: 1,
      stepLabels: ['Licenciados', 'Fechas', 'Exportar'],
      selectedDoctorIds: [],
      doctorSearch: '',
      dateFrom: today,
      dateTo: dates.addDaysToDate(today, 6),
      today,
      loadingPreview: false,
      loadingExport: false,
      previewData: null,
      datePresets: [
        { 
          label: 'Esta semana', 
          description: 'Hasta el domingo',
          getRange: () => this.getThisWeekRange()
        },
        { 
          label: 'Próxima semana', 
          description: 'Lunes a domingo',
          getRange: () => this.getNextWeekRange()
        },
        { 
          label: 'Próximos 7 días', 
          description: 'Desde hoy',
          getRange: () => ({ from: this.today, to: this.addDays(this.today, 6) })
        },
        { 
          label: 'Próximos 14 días', 
          description: 'Dos semanas',
          getRange: () => ({ from: this.today, to: this.addDays(this.today, 13) })
        },
      ],
    };
  },

  computed: {
    filteredDoctors() {
      if (!this.doctorSearch.trim()) return this.doctors;
      const search = this.doctorSearch.toLowerCase();
      return this.doctors.filter(d => d.name.toLowerCase().includes(search));
    },

    allDoctorsSelected() {
      return this.doctors.length > 0 && this.selectedDoctorIds.length === this.doctors.length;
    },

    someDoctorsSelected() {
      return this.selectedDoctorIds.length > 0;
    },

    daysCount() {
      if (!this.dateFrom || !this.dateTo) return 0;
      const from = dates.parseLocalDate(this.dateFrom);
      const to = dates.parseLocalDate(this.dateTo);
      return Math.ceil((to - from) / (1000 * 60 * 60 * 24)) + 1;
    },

    canProceed() {
      if (this.step === 1) {
        return this.selectedDoctorIds.length > 0;
      }
      if (this.step === 2) {
        return this.dateFrom && this.dateTo && this.daysCount > 0 && this.daysCount <= 14;
      }
      return true;
    },

    totalAvailableSlots() {
      if (!this.previewData?.availability) return 0;
      return this.previewData.availability.reduce((total, doctor) => {
        return total + doctor.days.reduce((dayTotal, day) => dayTotal + day.slots.length, 0);
      }, 0);
    },
  },

  watch: {
    show(newVal) {
      if (newVal) {
        this.resetWizard();
      }
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

    toggleAllDoctors() {
      if (this.allDoctorsSelected) {
        this.selectedDoctorIds = [];
      } else {
        this.selectedDoctorIds = this.doctors.map(d => d.id);
      }
    },

    addDays(dateStr, days) {
      return dates.addDaysToDate(dateStr, days);
    },

    getThisWeekRange() {
      const today = new Date();
      const dayOfWeek = today.getDay();
      const daysUntilSunday = dayOfWeek === 0 ? 0 : 7 - dayOfWeek;
      return {
        from: this.today,
        to: dates.addDaysToDate(this.today, daysUntilSunday),
      };
    },

    getNextWeekRange() {
      const today = new Date();
      const dayOfWeek = today.getDay();
      const daysUntilMonday = dayOfWeek === 0 ? 1 : 8 - dayOfWeek;
      const mondayStr = dates.addDaysToDate(this.today, daysUntilMonday);
      const sundayStr = dates.addDaysToDate(mondayStr, 6);
      return {
        from: mondayStr,
        to: sundayStr,
      };
    },

    applyPreset(preset) {
      const range = preset.getRange();
      this.dateFrom = range.from;
      this.dateTo = range.to;
    },

    isPresetActive(preset) {
      const range = preset.getRange();
      return this.dateFrom === range.from && this.dateTo === range.to;
    },

    formatDate(dateStr) {
      if (!dateStr) return '';
      const date = dates.parseLocalDate(dateStr);
      return date.toLocaleDateString('es-ES', { 
        weekday: 'short', 
        day: 'numeric', 
        month: 'short' 
      });
    },

    async nextStep() {
      if (this.step < 3 && this.canProceed) {
        this.step++;
        if (this.step === 3) {
          await this.loadPreview();
        }
      }
    },

    previousStep() {
      if (this.step > 1) {
        this.step--;
      }
    },

    async loadPreview() {
      this.loadingPreview = true;
      this.previewData = null;

      try {
        const response = await axios.post(route('scheduleOverview.previewAvailability'), {
          doctor_ids: this.selectedDoctorIds,
          date_from: this.dateFrom,
          date_to: this.dateTo,
        });
        this.previewData = response.data;
      } catch (error) {
        console.error('Error loading preview:', error);
      } finally {
        this.loadingPreview = false;
      }
    },

    async exportPdf() {
      this.loadingExport = true;

      try {
        const response = await axios.post(route('scheduleOverview.exportPdf'), {
          doctor_ids: this.selectedDoctorIds,
          date_from: this.dateFrom,
          date_to: this.dateTo,
        }, {
          responseType: 'blob',
        });

        const url = window.URL.createObjectURL(new Blob([response.data], { type: 'application/pdf' }));
        const link = document.createElement('a');
        link.href = url;
        
        const contentDisposition = response.headers['content-disposition'];
        let filename = 'disponibilidad-fisiosalud.pdf';
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

        this.closeWizard();
      } catch (error) {
        console.error('Error exporting PDF:', error);
        alert('Error al generar el PDF. Por favor, intente nuevamente.');
      } finally {
        this.loadingExport = false;
      }
    },

    closeWizard() {
      this.$emit('close');
    },

    resetWizard() {
      this.step = 1;
      this.selectedDoctorIds = [];
      this.doctorSearch = '';
      this.dateFrom = this.today;
      this.dateTo = dates.addDaysToDate(this.today, 6);
      this.previewData = null;
    },
  },
};
</script>

<style scoped>
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.3s ease;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}

.scale-enter-active,
.scale-leave-active {
  transition: all 0.3s ease;
}

.scale-enter-from,
.scale-leave-to {
  opacity: 0;
  transform: scale(0.95);
}
</style>
