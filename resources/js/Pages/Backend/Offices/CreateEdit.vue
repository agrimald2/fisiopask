<template>
  <app-layout title="Sucursales">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        <div class="flex items-center gap-4">
          <span
            class="underline cursor-pointer"
            @click="$inertia.visit(route('offices.index'))"
          >Sucursales</span>
          <i class="fas fa-angle-right"></i>
          {{ model ? 'Editar' : 'Crear' }} una Sucursal
        </div>
      </h2>
    </template>

    <div>
      <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">

        <!-- Default form -->
        <default-form
          :model="model"
          class="mt-10 sm:mt-0"
        />

        <jet-section-border />

        <div
          v-if="model"
          class="text-center px-4 mt-6"
        >
          <front-button
            color="red"
            @click="deleteOffice"
          >
            Eliminar esta Sucursal
          </front-button>
        </div>

      </div>
    </div>
  </app-layout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout.vue";
import JetSectionBorder from "@/Jetstream/SectionBorder.vue";

import FrontButton from "@/Shared/Frontend/Button";

import DefaultForm from "./Components/DefaultForm";

export default {
  props: ["model"],

  components: {
    AppLayout,
    JetSectionBorder,

    DefaultForm,
    FrontButton,
  },

  data() {
    return {
      //
    };
  },

  methods: {
    deleteOffice() {
      if (!confirm("Estas seguro?")) return;

      const url = route("offices.destroy", this.model.id);
      this.$inertia.delete(url);
    },
  },
};
</script>
