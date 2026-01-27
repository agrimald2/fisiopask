<template>
  <div 
    class="group relative rounded-xl border-2 p-3 transition-all duration-200 hover:shadow-md hover:scale-[1.02] cursor-default"
    :class="cardClasses"
    :style="cardStyles"
  >
    <!-- Color Indicator -->
    <div 
      class="absolute left-0 top-0 bottom-0 w-1 rounded-l-xl"
      :style="{ backgroundColor: indicatorColor }"
    ></div>

    <!-- Occupied Badge -->
    <div 
      v-if="schedule.is_occupied"
      class="absolute -top-2 -right-2 z-10"
    >
      <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-600 text-white shadow-sm">
        Ocupado
      </span>
    </div>

    <!-- Content -->
    <div class="pl-2">
      <!-- Time -->
      <div class="flex items-center gap-1.5 mb-2">
        <svg 
          class="w-3.5 h-3.5" 
          :class="schedule.is_occupied ? 'text-gray-400' : 'text-emerald-500'"
          fill="none" stroke="currentColor" viewBox="0 0 24 24"
        >
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <span 
          class="font-mono text-sm font-semibold"
          :class="schedule.is_occupied ? 'text-gray-500' : 'text-gray-700'"
        >
          {{ schedule.start_time }}
        </span>
        <svg 
          class="w-3 h-3" 
          :class="schedule.is_occupied ? 'text-gray-300' : 'text-gray-400'"
          fill="none" stroke="currentColor" viewBox="0 0 24 24"
        >
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
        <span 
          class="font-mono text-sm font-semibold"
          :class="schedule.is_occupied ? 'text-gray-500' : 'text-gray-700'"
        >
          {{ schedule.end_time }}
        </span>
      </div>

      <!-- Doctor Name -->
      <div class="flex items-center gap-2 mb-1.5">
        <div 
          class="w-5 h-5 rounded-full flex items-center justify-center text-white text-xs font-bold flex-shrink-0"
          :style="{ backgroundColor: schedule.is_occupied ? '#9CA3AF' : doctorColor }"
        >
          {{ schedule.doctor_name.charAt(0).toUpperCase() }}
        </div>
        <span 
          class="text-sm font-medium truncate" 
          :class="schedule.is_occupied ? 'text-gray-500' : 'text-gray-800'"
          :title="schedule.doctor_name"
        >
          {{ truncatedDoctorName }}
        </span>
      </div>

      <!-- Office -->
      <div class="flex items-center gap-1.5">
        <svg 
          class="w-3.5 h-3.5" 
          :class="schedule.is_occupied ? 'text-gray-300' : 'text-gray-400'"
          fill="none" stroke="currentColor" viewBox="0 0 24 24"
        >
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
        </svg>
        <span 
          class="text-xs truncate" 
          :class="schedule.is_occupied ? 'text-gray-400' : 'text-gray-500'"
          :title="schedule.office_name"
        >
          {{ schedule.office_name }}
        </span>
      </div>

      <!-- Patient Info (if occupied) -->
      <div v-if="schedule.is_occupied && schedule.patient_name" class="mt-2 pt-2 border-t border-gray-200">
        <div class="flex items-center gap-1.5">
          <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
          </svg>
          <span class="text-xs text-gray-500 truncate" :title="schedule.patient_name">
            {{ schedule.patient_name }}
          </span>
          <span 
            v-if="schedule.appointment_status_label"
            class="px-1.5 py-0.5 rounded text-xs font-medium ml-auto"
            :class="getStatusClass(schedule.appointment_status)"
          >
            {{ schedule.appointment_status_label }}
          </span>
        </div>
      </div>
    </div>

    <!-- Hover Info Tooltip -->
    <div 
      class="absolute left-full top-0 ml-2 z-20 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 pointer-events-none"
    >
      <div class="bg-gray-900 text-white text-xs rounded-lg px-3 py-2 whitespace-nowrap shadow-xl">
        <div class="font-semibold">{{ schedule.doctor_name }}</div>
        <div class="text-gray-300 mt-1">{{ schedule.office_name }}</div>
        <div class="text-gray-300">{{ schedule.start_time }} - {{ schedule.end_time }}</div>
        <div 
          class="mt-1 font-medium"
          :class="schedule.is_occupied ? 'text-amber-400' : 'text-emerald-400'"
        >
          {{ schedule.is_occupied ? 'Ocupado' : 'Disponible' }}
        </div>
        <div v-if="schedule.patient_name" class="text-gray-300 mt-1">
          Paciente: {{ schedule.patient_name }}
        </div>
        <div class="absolute left-0 top-3 -translate-x-1 w-2 h-2 bg-gray-900 rotate-45"></div>
      </div>
    </div>
  </div>
</template>

<script>
import str2hex from "@/ui/str2hex";

export default {
  props: {
    schedule: {
      type: Object,
      required: true,
    },
  },

  computed: {
    doctorColor() {
      return str2hex(this.schedule.doctor_name);
    },

    truncatedDoctorName() {
      const name = this.schedule.doctor_name;
      if (name.length > 15) {
        return name.substring(0, 15) + '...';
      }
      return name;
    },

    cardClasses() {
      if (this.schedule.is_occupied) {
        return 'bg-gray-100 border-gray-300';
      }
      return 'bg-emerald-50 border-emerald-200 hover:border-emerald-400';
    },

    cardStyles() {
      if (this.schedule.is_occupied) {
        return {};
      }
      return {
        borderColor: this.doctorColor + '60',
      };
    },

    indicatorColor() {
      if (this.schedule.is_occupied) {
        return '#9CA3AF';
      }
      return this.doctorColor;
    },
  },

  methods: {
    getStatusClass(status) {
      const classes = {
        1: 'bg-blue-100 text-blue-700',
        2: 'bg-red-100 text-red-700',
        3: 'bg-emerald-100 text-emerald-700',
        4: 'bg-gray-200 text-gray-600',
      };
      return classes[status] || 'bg-gray-100 text-gray-600';
    },
  },
};
</script>
