<template>
  <app-layout title="Añadir un datos">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        <span
          class="underline cursor-pointer"
        >Datos del Paciente</span>
        <i class="fas fa-angle-right mx-4"></i>
        Añadir datos al paciente
      </h2>
    </template>

    <div>
      <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">

        <!-- Default form -->
        <default-form
          class="mt-10 sm:mt-0"
          :patientId="patientId"
          :appointmentId="appointmentId"
          :showDistrict="showDistrict"
          :showRecommendation="showRecommendation"
          :recommendations="recommendationIdOptions"
        />

      </div>
    </div>
  </app-layout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout.vue";
import JetSectionBorder from "@/Jetstream/SectionBorder.vue";

import DefaultForm from "./Components/FillForm";

export default {
  props: ["patientId", "appointmentId", "showDistrict", "showRecommendation", "recommendations"],

  components: {
    AppLayout,
    JetSectionBorder,

    DefaultForm,
  },

  computed: {
    recommendationIdOptions() {
      let list = {};
      this.recommendations.map((x) => {
        list[x.id] = x.recommendation;
      });
      return list;
    }
  },
};
</script>
