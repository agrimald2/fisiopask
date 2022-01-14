<template>
  <app-layout title="Tarifas de la Cita">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Pagos de la Tarifa
      </h2>
    </template>

    <app-body>
      <div class="p-2">
        <div class="text-xl">Tarifa:</div>
        <span class="text-lg">{{ patientRate.name }}</span>

        <div class="mt-4 pt-4 border-t">
          <div class="text-xl">Pagos de la Tarifa:</div>
          <div class="py-4">
            <patient-payments-table :payments="payments" />
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
import PatientPaymentsTable from "./Components/PatientPaymentsTable.vue";

export default {
  props: ["patientRate", "payments"],

  components: {
    AppLayout,
    AppBody,
    UiCheckout,
    UiButton,

    PatientPaymentsTable,
  },
};
</script>
