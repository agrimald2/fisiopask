<template>
  <app-layout title="Añadir un pago">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Añadir un pago a {{ patient.name }}
      </h2>
    </template>

    <app-body>
      <div class="text-center pt-4">
        <div class="text-2xl">
          $ <span class="px-2 border select-all">{{ balance.balance }}</span>
        </div>
        <div class="text-xl">
          Saldo Deudor
        </div>
      </div>
      <form @submit.prevent="submit">
        <div class="p-2 grid gap-4">
          <div class="grid">
            <div class="font-bold">Método de Pago</div>
            <select v-model="form.payment_method_id">
              <option
                v-for="item, id in paymentMethodOptions"
                :key="id"
                :value="id"
              >{{ item }}</option>
            </select>
          </div>

          <div class="grid">
            <div class="font-bold">Cantidad</div>
            <input
              type="text"
              v-model="form.ammount"
            >
            <div
              class="underline text-sm cursor-pointer p-2 text-center"
              @click="form.ammount = balance.balance"
            >Click para cobrar el saldo deudor total</div>
          </div>

          <div class="grid">
            <div class="font-bold">Concepto (Opcional)</div>
            <input
              type="text"
              v-model="form.concept"
              placeholder="Descripcion"
            >
          </div>

          <ui-button
            color="green"
            type="submit"
          >
            Añadir pago
          </ui-button>
        </div>
      </form>
    </app-body>

  </app-layout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout.vue";
import AppBody from "@/Shared/Backend/AppBody";
import UiButton from "@/Shared/Frontend/Button";

export default {
  props: ["patient", "paymentMethodOptions", "balance"],

  components: {
    AppLayout,
    AppBody,
    UiButton,
  },

  methods: {
    submit() {
      const url = route("patients.rates.payments.store", this.patient.id);
      this.form.post(url);
    },
  },

  data() {
    return {
      form: this.$inertia.form({
        ammount: null,
        payment_method_id: null,
        concept: null,
      }),
    };
  },
};
</script>
