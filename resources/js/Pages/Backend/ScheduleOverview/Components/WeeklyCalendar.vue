<template>
  <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <!-- Loading Overlay -->
    <div 
      v-if="loading" 
      class="absolute inset-0 bg-white/80 backdrop-blur-sm z-10 flex items-center justify-center"
    >
      <div class="flex flex-col items-center gap-3">
        <svg class="w-10 h-10 text-indigo-500 animate-spin" fill="none" viewBox="0 0 24 24">
          <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
          <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        <span class="text-gray-500 font-medium">Cargando horarios...</span>
      </div>
    </div>

    <!-- Calendar Grid -->
    <div class="grid grid-cols-7 relative">
      <!-- Day Headers -->
      <div 
        v-for="(day, index) in days" 
        :key="day.key"
        class="border-b border-r border-gray-100 last:border-r-0"
      >
        <div 
          class="px-4 py-4 text-center cursor-pointer transition-all hover:bg-indigo-50"
          :class="isToday(index + 1) ? 'bg-indigo-100' : 'bg-gray-50'"
          @click="handleDayClick(index + 1)"
        >
          <div class="text-xs font-medium text-gray-500 uppercase tracking-wider">
            {{ day.short }}
          </div>
          <div 
            class="text-lg font-bold mt-1"
            :class="isToday(index + 1) ? 'text-indigo-600' : 'text-gray-800'"
          >
            {{ getDayNumber(index + 1) }}
          </div>
          <div v-if="isToday(index + 1)" class="mt-1">
            <span class="inline-block px-2 py-0.5 bg-indigo-500 text-white text-xs rounded-full font-medium">
              Hoy
            </span>
          </div>
        </div>
      </div>

      <!-- Schedule Columns -->
      <div 
        v-for="(day, index) in days" 
        :key="`content-${day.key}`"
        class="border-r border-gray-100 last:border-r-0 min-h-[500px]"
        :class="isToday(index + 1) ? 'bg-indigo-50/30' : ''"
      >
        <div class="p-2 space-y-2">
          <!-- Empty State -->
          <div 
            v-if="!schedules[index + 1] || schedules[index + 1].length === 0"
            class="flex flex-col items-center justify-center py-12 text-gray-400"
          >
            <svg class="w-8 h-8 mb-2 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
            </svg>
            <span class="text-xs">Sin horarios</span>
          </div>

          <!-- Schedule Cards -->
          <DoctorScheduleCard
            v-for="schedule in schedules[index + 1]"
            :key="schedule.id"
            :schedule="schedule"
          />
        </div>
      </div>
    </div>

    <!-- Summary Footer -->
    <div class="bg-gray-50 border-t border-gray-100 px-6 py-4">
      <div class="flex items-center justify-between flex-wrap gap-4">
        <div class="flex items-center gap-4 flex-wrap">
          <!-- Disponibles -->
          <div class="flex items-center gap-2">
            <div class="w-3 h-3 rounded-full bg-emerald-400"></div>
            <span class="text-sm text-gray-600">
              <span class="font-semibold text-emerald-600">{{ availableSchedules }}</span> disponibles
            </span>
          </div>
          <!-- Ocupados -->
          <div class="flex items-center gap-2">
            <div class="w-3 h-3 rounded-full bg-gray-400"></div>
            <span class="text-sm text-gray-600">
              <span class="font-semibold text-gray-600">{{ occupiedSchedules }}</span> ocupados
            </span>
          </div>
          <!-- Porcentaje de ocupación -->
          <div class="flex items-center gap-2 px-3 py-1 bg-white rounded-full border border-gray-200">
            <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
            </svg>
            <span class="text-sm font-semibold text-indigo-600">{{ occupancyPercentage }}%</span>
            <span class="text-xs text-gray-500">ocupación</span>
          </div>
          <!-- Doctores -->
          <div class="flex items-center gap-2">
            <svg class="w-4 h-4 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
            <span class="text-sm text-gray-600">
              <span class="font-semibold">{{ uniqueDoctors }}</span> doctores
            </span>
          </div>
        </div>
        <div class="text-sm text-gray-500">
          Haz clic en un día para ver detalles
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import DoctorScheduleCard from "./DoctorScheduleCard.vue";

export default {
  props: {
    schedules: {
      type: Object,
      required: true,
    },
    weekStart: {
      type: String,
      required: true,
    },
    loading: {
      type: Boolean,
      default: false,
    },
  },

  emits: ['day-click'],

  components: {
    DoctorScheduleCard,
  },

  data() {
    return {
      days: [
        { key: 1, name: 'Lunes', short: 'Lun' },
        { key: 2, name: 'Martes', short: 'Mar' },
        { key: 3, name: 'Miércoles', short: 'Mié' },
        { key: 4, name: 'Jueves', short: 'Jue' },
        { key: 5, name: 'Viernes', short: 'Vie' },
        { key: 6, name: 'Sábado', short: 'Sáb' },
        { key: 7, name: 'Domingo', short: 'Dom' },
      ],
    };
  },

  computed: {
    totalSchedules() {
      let total = 0;
      for (let day = 1; day <= 7; day++) {
        if (this.schedules[day]) {
          total += this.schedules[day].length;
        }
      }
      return total;
    },

    occupiedSchedules() {
      let total = 0;
      for (let day = 1; day <= 7; day++) {
        if (this.schedules[day]) {
          total += this.schedules[day].filter(s => s.is_occupied).length;
        }
      }
      return total;
    },

    availableSchedules() {
      return this.totalSchedules - this.occupiedSchedules;
    },

    occupancyPercentage() {
      if (this.totalSchedules === 0) return 0;
      return Math.round((this.occupiedSchedules / this.totalSchedules) * 100);
    },

    uniqueDoctors() {
      const doctorIds = new Set();
      for (let day = 1; day <= 7; day++) {
        if (this.schedules[day]) {
          this.schedules[day].forEach(s => doctorIds.add(s.doctor_id));
        }
      }
      return doctorIds.size;
    },
  },

  methods: {
    getDateForDay(dayIndex) {
      // Usar formato con T00:00:00 para evitar problemas de zona horaria
      // Sin esto, "2026-01-26" se interpreta como UTC y puede retroceder un día
      const [year, month, day] = this.weekStart.split('-').map(Number);
      const start = new Date(year, month - 1, day); // month es 0-indexed
      start.setDate(start.getDate() + (dayIndex - 1));
      return start;
    },

    getDayNumber(dayIndex) {
      return this.getDateForDay(dayIndex).getDate();
    },

    isToday(dayIndex) {
      const dayDate = this.getDateForDay(dayIndex);
      const today = new Date();
      return dayDate.toDateString() === today.toDateString();
    },

    handleDayClick(dayIndex) {
      const date = this.getDateForDay(dayIndex);
      this.$emit('day-click', {
        date: date.toISOString().split('T')[0],
        dayName: this.days[dayIndex - 1].name,
        dayIndex,
      });
    },
  },
};
</script>
