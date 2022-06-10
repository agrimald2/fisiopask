<template>
  <app-layout title="Tarifas de la Cita">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Tarifas del Paciente
      </h2>
    </template>

    <app-body>
      <div class="p-2">
        <div class="text-xl">Paciente:</div>
        <span class="text-lg">{{ patient.fullname }}</span>

        <div class="mt-4 pt-4 border-t">
          <div class="text-xl">Tarifas del Paciente:</div>
          <div class="py-4">
            <patient-rates-table :patientRates="patient.rates" :appointment="appointment" style="overflow-x: auto;"/>
          </div>
        </div>

      </div>
    </app-body>
  </app-layout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout.vue";
import AppBody from "@/Shared/Backend/AppBody";

import UiButton from "@/Shared/Frontend/Button";
import UiCheckout from "@/Pages/Backend/PatientRates/Components/Checkout.vue";
import PatientRatesTable from "./Components/PatientRatesTable.vue";

export default {
  props: ["patient", "appointment"],

  components: {
    AppLayout,
    AppBody,
    UiCheckout,
    UiButton,

    PatientRatesTable,
  },
};
</script>
