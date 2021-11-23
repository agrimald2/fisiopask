<template>
  <app-layout title="Añadir un Doctor">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        <span
          class="underline cursor-pointer"
          @click="$inertia.visit(route('doctors.index'))"
        >Doctores</span>
        <i class="fas fa-angle-right mx-4"></i>
        {{ model ? "Editar " : "Crear un nuevo" }} Doctor
      </h2>
    </template>

    <div>
      <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">

        <!-- Default form -->
        <default-form
          class="mt-10 sm:mt-0"
          :model="model"
        />

        <div v-if="model">
          <jet-section-border />

          <specialties-form
            :model="model"
            :specialties="specialties"
          />
        </div>

      </div>
    </div>
  </app-layout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout.vue";
import JetSectionBorder from "@/Jetstream/SectionBorder.vue";

import DefaultForm from "./Components/Form";

import SpecialtiesForm from "./Components/SpecialtiesForm";

export default {
  props: ["model", "specialties"],

  components: {
    AppLayout,
    JetSectionBorder,

    DefaultForm,

    SpecialtiesForm,
  },

  data() {
    return {
      //
    };
  },

  methods: {},
};
</script>
