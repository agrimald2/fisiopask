<template>
  <app-layout title="Horarios">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        <span
          class="underline cursor-pointer"
          @click="$inertia.visit(route('doctors.index'))"
        >Doctores</span>
        <i class="fas fa-angle-right mx-4"></i>
        Horario de {{ doctor.user.name }}
      </h2>
    </template>

    <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">

      <!-- Default form -->
      <default-form
        :doctor="doctor"
        :offices="offices"
        class="mt-10 sm:mt-0"
      />

    </div>

    <jet-section-border />

    <!-- Week view -->
    <div class="pb-12">
      <div class="text-3xl text-center">Horario Semanal</div>
      <div class="mt-2 text-center text-gray-500">Haz click sobre un horario para eliminarlo.</div>
      <div class="pt-6"></div>
      <week-view :schedules="doctor.schedules" />
    </div>

    <!-- <app-body></app-body> -->

  </app-layout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout.vue";
import AppBody from "@/Shared/Backend/AppBody";

import JetSectionBorder from "@/Jetstream/SectionBorder.vue";
import DefaultForm from "./Components/Form";

import WeekView from "./Components/Week";

export default {
  props: ["doctor", "offices"],

  components: {
    AppLayout,
    AppBody,
    JetSectionBorder,

    DefaultForm,
    WeekView,
  },

  data() {
    return {
      //
    };
  },
};
</script>
