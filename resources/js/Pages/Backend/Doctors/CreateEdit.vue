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
          :workspaces="workspaceIdOptions"
        />

        <div v-if="model">
          <jet-section-border />

          <specialties-form
            :model="model"
            :specialties="specialties"
          />

          <jet-section-border />

          <subfamilies-form
            :model="model"
            :subfamilies="subfamilies"
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
import SubfamiliesForm from "./Components/SubfamiliesForm";

export default {
  props: ["model", "specialties", "subfamilies", "workspaces"],

  components: {
    AppLayout,
    JetSectionBorder,

    DefaultForm,

    SpecialtiesForm,
    SubfamiliesForm,
  },

  computed: {
    workspaceIdOptions() {
      let list = {};
      this.workspaces.map((x) => {
        list[x.id] = x.name;
      });
      return list;
    }
  },

  data() {
    return {
      //
    };
  },

  methods: {},
};
</script>
