<template>
  <app-layout title="Punto de Venta">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Punto de Venta
      </h2>
    </template>

    <app-body>
      <ui-checkout
        :patient="patient"
        :submitUrl="route('patients.rates.store', patient.id)"
      />
    </app-body>

  </app-layout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout.vue";
import AppBody from "@/Shared/Backend/AppBody";

import UiCheckout from "./Components/Checkout.vue";

export default {
  props: ["patient"],

  components: {
    AppLayout,
    AppBody,
    UiCheckout,
  },
};
</script>
