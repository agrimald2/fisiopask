<template>
  <app-layout title="Añadir un Encargado">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        <span
          class="underline cursor-pointer"
          @click="$inertia.visit(route('workers.index'))"
        >Encargado</span>
        <i class="fas fa-angle-right mx-4"></i>
        {{ model ? "Editar " : "Crear un nuevo" }} Encargado
      </h2>
    </template>

    <div>
      <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">

        <!-- Default form -->
        <default-form
          class="mt-10 sm:mt-0"
          :model="model"
          :companies="companiesIdOptions"
        />

      </div>
    </div>
  </app-layout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout.vue";
import JetSectionBorder from "@/Jetstream/SectionBorder.vue";

import DefaultForm from "./Components/Form";

export default {
  props: ["model","companies"],

  components: {
    AppLayout,
    JetSectionBorder,

    DefaultForm,
  },

    computed: {
    companiesIdOptions() {
      let list = {};
      this.companies.map((x) => {
        list[x.id] = x.name;
      });
      return list;
    }
  },
};
</script>
