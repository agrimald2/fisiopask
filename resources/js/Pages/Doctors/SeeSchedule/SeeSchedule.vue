<template>
  <app-layout title="Mi Horario">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Mi Horario
      </h2>
    </template>

    <app-body>
      <div class="py-4">
        <div class="text-xl text-center">{{ model.name }} {{ model.lastname }}</div>
        <doctor-schedules :model="model.schedules" />
      </div>
    </app-body>

  </app-layout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout.vue";
import AppBody from "@/Shared/Backend/AppBody";

import DoctorSchedules from "./Components/Schedules";

export default {
  props: ["model"],

  components: {
    AppLayout,
    AppBody,

    DoctorSchedules,
  },

  data() {
    return {
      //
    };
  },
};
</script>
