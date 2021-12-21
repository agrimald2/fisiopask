<template>
  <app-layout title="Diagnóstico">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        <div class="flex items-center gap-4">
          <span
            class="underline cursor-pointer"
            @click="$inertia.visit(route('diagnostic.index'))"
          >Diagnósticos</span>
          <i class="fas fa-angle-right"></i>
          {{ model ? 'Editar' : 'Crear' }} un Diagnóstico
        </div>
      </h2>
    </template>

    <div>
      <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
        <!-- Default form -->
        <DefaultForm
          :model="model"
          class="mt-10 sm:mt-0"
        />

        <JetSectionBorder/>

        <div
          v-if="model"
          class="text-center px-4 mt-6"
        >
          <front-button
            color="red"
            @click="deleteObject"
          >
            Eliminar este Diagnóstico
          </front-button>
        </div>
      </div>
    </div>
  </app-layout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout.vue";
import DefaultForm from "./Components/DefaultForm";

import JetSectionBorder from "@/Jetstream/SectionBorder.vue";
import FrontButton from "@/Shared/Frontend/Button";

export default {
  props: ['model'],

  components: {
    AppLayout,
    DefaultForm,

    JetSectionBorder,
    FrontButton,
  },

  methods: {
    deleteObject() {
      if (!confirm("Estas seguro?")) return;

      const url = route("diagnostic.destroy", this.model.id);
      this.$inertia.delete(url);
    },
  }
}
</script>