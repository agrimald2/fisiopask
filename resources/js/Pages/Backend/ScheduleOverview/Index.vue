<template>
  <app-layout title="Horarios de Doctores">
    <div class="py-6">
      <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
          <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
              <div class="p-3 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl shadow-lg">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
              </div>
              <div>
                <h1 class="text-2xl font-bold text-gray-800">Horarios de Doctores</h1>
                <p class="text-gray-500">Visualiza y gestiona los horarios de todos los doctores</p>
              </div>
            </div>

            <!-- Week Navigation -->
            <div class="flex items-center gap-3">
              <button
                @click="previousWeek"
                class="p-2 rounded-lg bg-white border border-gray-200 hover:bg-gray-50 hover:border-indigo-300 transition-all shadow-sm"
              >
                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
              </button>
              
              <div class="px-4 py-2 bg-white rounded-lg border border-gray-200 shadow-sm">
                <span class="font-semibold text-gray-700">{{ formattedWeekRange }}</span>
              </div>

              <button
                @click="nextWeek"
                class="p-2 rounded-lg bg-white border border-gray-200 hover:bg-gray-50 hover:border-indigo-300 transition-all shadow-sm"
              >
                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
              </button>

              <button
                @click="goToToday"
                class="px-4 py-2 rounded-lg bg-indigo-500 text-white font-medium hover:bg-indigo-600 transition-all shadow-sm"
              >
                Hoy
              </button>

              <!-- Export Button -->
              <button
                @click="showExportWizard = true"
                class="px-4 py-2 rounded-lg bg-gradient-to-r from-[#0cb8b6] to-[#0a9f9d] text-white font-medium hover:shadow-lg hover:shadow-[#0cb8b6]/30 transition-all shadow-sm flex items-center gap-2"
              >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                Exportar PDF
              </button>
            </div>
          </div>
        </div>

        <!-- Main Content -->
        <div class="grid grid-cols-1 xl:grid-cols-5 gap-6">
          <!-- Filters Panel -->
          <div class="xl:col-span-1">
            <Filters
              :doctors="doctors"
              :offices="offices"
              :selected-doctor-ids="selectedDoctorIds"
              :selected-office-id="selectedOfficeId"
              :selected-date="selectedDate"
              @update:doctorIds="updateDoctorFilter"
              @update:officeId="updateOfficeFilter"
              @update:date="updateDateFilter"
              @clear-filters="clearFilters"
            />

            <!-- Legend -->
            <Legend 
              :doctors="filteredDoctors"
              class="mt-6"
            />
          </div>

          <!-- Calendar View -->
          <div class="xl:col-span-4">
            <WeeklyCalendar
              :schedules="localSchedules"
              :week-start="localWeekStart"
              :loading="loading"
              @day-click="handleDayClick"
            />
          </div>
        </div>

        <!-- Day Detail Modal -->
        <teleport to="body">
          <div 
            v-if="showDayModal" 
            class="fixed inset-0 z-50 flex items-center justify-center"
          >
            <div 
              class="absolute inset-0 bg-black/50 backdrop-blur-sm"
              @click="closeDayModal"
            ></div>
            <div class="relative bg-white rounded-2xl shadow-2xl max-w-2xl w-full mx-4 max-h-[80vh] overflow-hidden">
              <div class="px-6 py-4 bg-gradient-to-r from-indigo-500 to-purple-600 text-white">
                <div class="flex items-center justify-between">
                  <div>
                    <h3 class="text-lg font-semibold">{{ dayModalData.dayName }}</h3>
                    <p class="text-indigo-100 text-sm">{{ dayModalData.formattedDate }}</p>
                  </div>
                  <button 
                    @click="closeDayModal"
                    class="p-2 rounded-lg hover:bg-white/20 transition-colors"
                  >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                  </button>
                </div>
              </div>
              <div class="p-6 overflow-y-auto max-h-[60vh]">
                <div v-if="dayModalData.loading" class="flex items-center justify-center py-8">
                  <svg class="w-8 h-8 text-indigo-500 animate-spin" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                  </svg>
                </div>
                <div v-else-if="dayModalData.schedules.length === 0" class="text-center py-8 text-gray-500">
                  No hay horarios programados para este día
                </div>
                <div v-else class="space-y-3">
                  <div 
                    v-for="schedule in dayModalData.schedules" 
                    :key="schedule.id"
                    class="p-4 rounded-xl border transition-all"
                    :class="schedule.is_available 
                      ? 'bg-emerald-50 border-emerald-200' 
                      : 'bg-amber-50 border-amber-200'"
                  >
                    <div class="flex items-center justify-between">
                      <div class="flex items-center gap-3">
                        <div 
                          class="w-3 h-3 rounded-full"
                          :style="{ backgroundColor: getDoctorColor(schedule.doctor_name) }"
                        ></div>
                        <div>
                          <div class="font-semibold text-gray-800">{{ schedule.doctor_name }}</div>
                          <div class="text-sm text-gray-500">{{ schedule.office_name }}</div>
                        </div>
                      </div>
                      <div class="text-right">
                        <div class="font-mono font-semibold text-gray-700">
                          {{ schedule.start_time }} - {{ schedule.end_time }}
                        </div>
                        <div 
                          class="text-sm font-medium"
                          :class="schedule.is_available ? 'text-emerald-600' : 'text-amber-600'"
                        >
                          {{ schedule.is_available ? 'Disponible' : 'Ocupado' }}
                        </div>
                      </div>
                    </div>
                    <div v-if="schedule.appointment" class="mt-3 pt-3 border-t border-amber-200">
                      <div class="flex items-center gap-2 text-sm">
                        <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        <span class="text-gray-600">{{ schedule.appointment.patient_name }}</span>
                        <span 
                          class="px-2 py-0.5 rounded-full text-xs font-medium"
                          :class="getStatusClass(schedule.appointment.status)"
                        >
                          {{ schedule.appointment.status_label }}
                        </span>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </teleport>

        <!-- Export Wizard Modal -->
        <ExportWizard
          :show="showExportWizard"
          :doctors="doctors"
          @close="showExportWizard = false"
        />
      </div>
    </div>
  </app-layout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout.vue";
import Filters from "./Components/Filters.vue";
import WeeklyCalendar from "./Components/WeeklyCalendar.vue";
import Legend from "./Components/Legend.vue";
import ExportWizard from "./Components/ExportWizard.vue";
import str2hex from "@/ui/str2hex";
import axios from "axios";

export default {
  props: {
    doctors: Array,
    offices: Array,
    schedules: Object,
    weekStart: String,
    weekEnd: String,
    currentDate: String,
    filters: Object,
  },

  components: {
    AppLayout,
    Filters,
    WeeklyCalendar,
    Legend,
    ExportWizard,
  },

  data() {
    return {
      localSchedules: this.schedules,
      localWeekStart: this.weekStart,
      localWeekEnd: this.weekEnd,
      selectedDoctorIds: this.filters?.doctor_ids || [],
      selectedOfficeId: this.filters?.office_id || null,
      selectedDate: this.currentDate,
      loading: false,
      showDayModal: false,
      showExportWizard: false,
      dayModalData: {
        date: null,
        dayName: '',
        formattedDate: '',
        schedules: [],
        loading: false,
      },
    };
  },

  computed: {
    formattedWeekRange() {
      const start = new Date(this.localWeekStart);
      const end = new Date(this.localWeekEnd);
      
      const startDay = start.getDate();
      const endDay = end.getDate();
      const startMonth = start.toLocaleDateString('es-ES', { month: 'short' });
      const endMonth = end.toLocaleDateString('es-ES', { month: 'short' });
      const year = end.getFullYear();

      if (startMonth === endMonth) {
        return `${startDay} - ${endDay} ${startMonth} ${year}`;
      }
      return `${startDay} ${startMonth} - ${endDay} ${endMonth} ${year}`;
    },

    filteredDoctors() {
      if (this.selectedDoctorIds.length === 0) {
        return this.doctors;
      }
      return this.doctors.filter(d => this.selectedDoctorIds.includes(d.id));
    },
  },

  methods: {
    getDoctorColor(name) {
      return str2hex(name);
    },

    async fetchSchedules() {
      this.loading = true;
      try {
        const response = await axios.post(route('scheduleOverview.filter'), {
          date: this.selectedDate,
          doctor_ids: this.selectedDoctorIds,
          office_id: this.selectedOfficeId,
        });

        this.localSchedules = response.data.schedules;
        this.localWeekStart = response.data.weekStart;
        this.localWeekEnd = response.data.weekEnd;
      } catch (error) {
        console.error('Error fetching schedules:', error);
      } finally {
        this.loading = false;
      }
    },

    previousWeek() {
      const date = new Date(this.localWeekStart);
      date.setDate(date.getDate() - 7);
      this.selectedDate = date.toISOString().split('T')[0];
      this.fetchSchedules();
    },

    nextWeek() {
      const date = new Date(this.localWeekStart);
      date.setDate(date.getDate() + 7);
      this.selectedDate = date.toISOString().split('T')[0];
      this.fetchSchedules();
    },

    goToToday() {
      this.selectedDate = new Date().toISOString().split('T')[0];
      this.fetchSchedules();
    },

    updateDoctorFilter(ids) {
      this.selectedDoctorIds = ids;
      this.fetchSchedules();
    },

    updateOfficeFilter(id) {
      this.selectedOfficeId = id;
      this.fetchSchedules();
    },

    updateDateFilter(date) {
      this.selectedDate = date;
      this.fetchSchedules();
    },

    clearFilters() {
      this.selectedDoctorIds = [];
      this.selectedOfficeId = null;
      this.fetchSchedules();
    },

    async handleDayClick(dayData) {
      this.showDayModal = true;
      this.dayModalData = {
        date: dayData.date,
        dayName: dayData.dayName,
        formattedDate: new Date(dayData.date).toLocaleDateString('es-ES', { 
          weekday: 'long', 
          year: 'numeric', 
          month: 'long', 
          day: 'numeric' 
        }),
        schedules: [],
        loading: true,
      };

      try {
        const response = await axios.post(route('scheduleOverview.dayDetail'), {
          date: dayData.date,
          doctor_ids: this.selectedDoctorIds,
          office_id: this.selectedOfficeId,
        });

        this.dayModalData.schedules = response.data.schedules;
      } catch (error) {
        console.error('Error fetching day detail:', error);
      } finally {
        this.dayModalData.loading = false;
      }
    },

    closeDayModal() {
      this.showDayModal = false;
    },

    getStatusClass(status) {
      const classes = {
        1: 'bg-blue-100 text-blue-700',
        2: 'bg-red-100 text-red-700',
        3: 'bg-emerald-100 text-emerald-700',
        4: 'bg-gray-100 text-gray-700',
      };
      return classes[status] || 'bg-gray-100 text-gray-700';
    },
  },
};
</script>
