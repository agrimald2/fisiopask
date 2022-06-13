<template>
  <app-layout title="Historia Médica">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        <div class="flex items-center gap-4">
          <span
            class="underline cursor-pointer"
            @click="$inertia.visit(route('patients.historygroup'))"
          >Historia Médica</span>
          <i class="fas fa-angle-right"></i>
          Crear una Historia Médica
        </div>
      </h2>
    </template>

    <div>
      <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
        <!-- Default form -->
        <DefaultForm
          class="mt-10 sm:mt-0"
          :types="types"
          :patientId="patientId"
          :doctorId="doctorId"
        />
      </div>
    </div>
  </app-layout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout.vue";
import DefaultForm from "./Components/SelectForm";

export default {
  props: ["patientId", "doctorId", "types"],

  components: {
    AppLayout,
    DefaultForm,
  },
}
</script>