<template>
  <app-layout title="Atributos de Historias Clínicas">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        <div class="flex items-center gap-4">
          <span
            class="underline cursor-pointer"
            @click="$inertia.visit(route('hc.attributes.index'))"
          >Atributos</span>
          <i class="fas fa-angle-right"></i>
          {{ model ? 'Editar' : 'Crear' }} un Atributo
        </div>
      </h2>
    </template>

    <div>
      <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
        <!-- Default form -->
        <DefaultForm
          :model="model"
          :models="models"
          :id="id"
          :types="types"
          class="mt-10 sm:mt-0"
        />
      </div>
    </div>
  </app-layout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout.vue";
import DefaultForm from "./Components/Form";

import JetSectionBorder from "@/Jetstream/SectionBorder.vue";
import FrontButton from "@/Shared/Frontend/Button";

export default {
  props: ['model', 'models', 'id', 'types'],

  components: {
    AppLayout,
    DefaultForm,

    JetSectionBorder,
    FrontButton,
  },
}
</script>