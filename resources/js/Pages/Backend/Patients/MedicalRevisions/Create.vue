<template>
  <app-layout title="Revisión Médica">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        <div class="flex items-center gap-4">
          <span
            class="underline cursor-pointer"
            @click="$inertia.visit(route('patients.historygroup'))"
          >Revisión Médica</span>
          <i class="fas fa-angle-right"></i>
          Crear una Revisión Médica
        </div>
      </h2>
    </template>

    <div>
      <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
        <!-- Default form -->
        <DefaultForm
          class="mt-10 sm:mt-0"
          :history_group="history_group"
          :diagnostics="diagnosticsOptions"
          :treatments="treatmentsOptions"
          :analysis="analysisOptions"
          :affected_areas="affectedAreasOptions"
          :attributes="attributes"
        />
      </div>
    </div>
  </app-layout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout.vue";
import DefaultForm from "./Components/DefaultForm";

export default {
  props: ["history_group", "diagnostics", "treatments", "analysis", "affected_areas", "attributes"],

  components: {
    AppLayout,
    DefaultForm,
  },

  computed: {
    diagnosticsOptions() {
      let list = {};
      this.diagnostics.map((x) => {
        list[x.id] = x.cie_10 + " - " + x.name;
      });
      return list;
    },

    treatmentsOptions() {
      let list = {};
      this.treatments.map((x) => {
        list[x.id] = x.name;
      });
      return list;
    },

    analysisOptions() {
      let list = {};
      this.analysis.map((x) => {
        list[x.id] = x.name;
      });
      return list;
    },

    affectedAreasOptions() {
      let list = {};
      this.affected_areas.map((x) => {
        list[x.id] = x.sub_category;
      });
      return list;
    },    
  },
}
</script>