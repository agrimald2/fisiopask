<template>
  <app-layout title="Revisión Médica">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        <div class="flex items-center gap-4">
          <span
            class="underline cursor-pointer"
            @click="$inertia.visit(route('patients.historygroup.index', history_group.id))"
          >Revisión Médica</span>
          <i class="fas fa-angle-right"></i>
          Crear una Revisión
        </div>
      </h2>
    </template>

    <div>
      <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
        <!-- Default form -->
        <DefaultForm
          class="mt-10 sm:mt-0"
          :history_group="history_group"
          :treatments="treatmentsOptions"
        />
      </div>
    </div>
  </app-layout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout.vue";
import DefaultForm from "./Components/DefaultForm";

export default {
  props: ["history_group", "treatments"],

  components: {
    AppLayout,
    DefaultForm,
  },

  computed: {
    treatmentsOptions() {
      let list = {};
      this.treatments.map((x) => {
        list[x.id] = x.name;
      });
      return list;
    },   
  },
}
</script>