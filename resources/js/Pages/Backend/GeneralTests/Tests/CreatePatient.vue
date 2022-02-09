<template>
  <app-layout title="Crear un Paciente">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        <span
          class="underline cursor-pointer"
          @click="$inertia.visit(route('tests.index'))"
        >Tests</span>
        <i class="fas fa-angle-right mx-4"></i>
        Crear Paciente
      </h2>
    </template>

    <div>
      <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">

        <!-- Create Form -->
        <PatientForm
          class="mt-10 sm:mt-0"
          :dni="dni"
        />

      </div>
    </div>
  </app-layout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout.vue";
import JetSectionBorder from "@/Jetstream/SectionBorder.vue";

import PatientForm from "./Components/PatientForm";

export default {
  props: ['dni'],
  
  components: {
    AppLayout,
    JetSectionBorder,

    PatientForm,
  },
};
</script>
