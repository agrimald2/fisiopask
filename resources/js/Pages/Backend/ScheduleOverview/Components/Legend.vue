<template>
  <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="px-5 py-4 bg-gradient-to-r from-gray-50 to-white border-b border-gray-100">
      <div class="flex items-center gap-2">
        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01" />
        </svg>
        <h4 class="font-semibold text-gray-700 text-sm">Leyenda de Doctores</h4>
      </div>
    </div>

    <div class="p-4">
      <div v-if="doctors.length === 0" class="text-center py-4 text-gray-400 text-sm">
        No hay doctores disponibles
      </div>

      <div v-else class="space-y-2 max-h-64 overflow-y-auto">
        <div 
          v-for="doctor in doctors" 
          :key="doctor.id"
          class="flex items-center gap-3 p-2 rounded-lg hover:bg-gray-50 transition-colors"
        >
          <div 
            class="w-4 h-4 rounded-full flex-shrink-0 ring-2 ring-offset-1"
            :style="{ 
              backgroundColor: getDoctorColor(doctor.name),
              ringColor: getDoctorColor(doctor.name) + '40'
            }"
          ></div>
          <span class="text-sm text-gray-700 truncate" :title="doctor.name">
            {{ doctor.name }}
          </span>
        </div>
      </div>

      <!-- Summary -->
      <div class="mt-4 pt-4 border-t border-gray-100">
        <div class="flex items-center justify-between text-xs text-gray-500">
          <span>Total de doctores</span>
          <span class="font-semibold text-gray-700">{{ doctors.length }}</span>
        </div>
      </div>
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
  },

  methods: {
    getDoctorColor(name) {
      return str2hex(name);
    },
  },
};
</script>
