<template>
  <app-layout title="Crear un Test">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        <span
          class="underline cursor-pointer"
          @click="$inertia.visit(route('tests.index'))"
        >Tests</span>
        <i class="fas fa-angle-right mx-4"></i>
        {{ model ? "Editar " : "Crear un " }} test
      </h2>
    </template>

    <div>
      <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">

        <!-- Default form -->
        <DefaultForm
          class="mt-10 sm:mt-0"
          :model="model"
          :doctorsMap="doctorsMapped"
          :companiesMap="companiesMapped"
          :testTypesMap="testTypesMapped"
          :testTypes="testTypes"
          :resultsArray="resultsArray"
          :patient_id="patient_id"
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
  props: ["model", "doctors", "companies", "testTypes", "resultsArray", "patient_id"],

  components: {
    AppLayout,
    JetSectionBorder,

    DefaultForm,
  },

  computed: {
    doctorsMapped() {
        let list = {};
        this.doctors.map((x) => {
            list[x.id] = x.fullname;
        });
        return list;
    },

    companiesMapped() {
        let list = {};
        this.companies.map((x) => {
            list[x.id] = x.name;
        });
        return list;
    },

    testTypesMapped() {
        let list = {};
        this.testTypes.map((x) => {
            list[x.id] = x.name;
        });
        return list;
    }
  },
};
</script>
