<template>
  <app-layout title="Crear un Test">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        <span
          class="underline cursor-pointer"
          @click="$inertia.visit(route('tests.index'))"
        >Tests</span>
        <i class="fas fa-angle-right mx-4"></i>
        Buscar Paciente
      </h2>
    </template>

    <div>
      <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">

        <!-- Lookup Form -->
        <DNILookupForm
          class="mt-10 sm:mt-0"
        />

      </div>
    </div>
  </app-layout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout.vue";
import JetSectionBorder from "@/Jetstream/SectionBorder.vue";

import DNILookupForm from "./Components/DNILookupForm";

export default {
  components: {
    AppLayout,
    JetSectionBorder,

    DNILookupForm,
  },
};
</script>
