<template>
  <app-layout title="Generar Link de Pago">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Generar Link de Pago
      </h2>
    </template>

    <app-body>
      <div class="text-center py-4">
        <div class="text-2xl">{{ patient.fullname }}</div>
        <div class="text-xl">Introduce cantidad a solicitar:</div>
        <div class="mt-4">
          <input
            type="text"
            class="border p-4 rounded text-center border-gray-400 text-xl"
            :placeholder="balanceDebtOnly"
            v-model="input.amount"
          >
          <div
            class="underline text-blue-500 text-lg cursor-pointer py-2"
            @click="addTotalBalance"
          >Añadir el saldo total</div>
        </div>
        <div class="mt-4">
          <ui-button
            color="green"
            @click="generate"
          >Generar link de pago</ui-button>
        </div>
        <div class="mt-4">
          Este paciente debe: ${{ fix(balance.debts) }}
          <br>
          Este paciente ha pagado: ${{ fix(balance.payments) }}
          <br>
          La diferencia para saldar su cuenta es: ${{ fix(balance.balance) }}
        </div>
      </div>
    </app-body>

  </app-layout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout.vue";
import AppBody from "@/Shared/Backend/AppBody";

import UiButton from "@/Shared/Frontend/Button";

export default {
  props: ["patient", "balance"],

  components: {
    AppLayout,
    AppBody,
    UiButton,
  },

  data() {
    return {
      input: { amount: null },
    };
  },

  methods: {
    fix(x) {
      return parseFloat(x).toFixed(2);
    },

    addTotalBalance() {
      this.input.amount = this.balanceDebtOnly;
    },

    generate() {
      const link = route("paynow.index", {
        patient: this.patient.id,
        amount: this.input.amount,
      });
      prompt("Copie y envíe el link de pago", link);
    },
  },

  computed: {
    balanceDebtOnly() {
      return this.balance.balance > 0 ? this.fix(this.balance.balance) : 0;
    },
  },
};
</script>
