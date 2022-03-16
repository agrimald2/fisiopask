<template>
  <app-layout title="Dashboard">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Dashboard
      </h2>
    </template>

    <template v-if="roles.includes('doctor')">
      <doctor-appointments :model="doctorAppointments" />
      <doctor-links />
    </template>

    <template v-if="roles.includes('admin')">
      <admin-links />
    </template>

    <template v-if="roles.includes('assistant')">
      <assistant-links />
    </template>

    <div class="py-24"></div>

  </app-layout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout.vue";

import AdminLinks from "./Admins/Links";
import DoctorLinks from "./Doctors/Links";
import AssistantLinks from "./Assistants/Links";

import DoctorAppointments from "./Doctors/Appointments/Appointments.vue";

export default {
  props: ["roles", "doctorSchedules", "doctorAppointments"],

  components: {
    AppLayout,

    AdminLinks,
    DoctorLinks,
    AssistantLinks,

    DoctorAppointments,
  },

  data() {
    return {
      //
    };
  },
};
</script>
