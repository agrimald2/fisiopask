<template>
  <app-layout title="Productos y Servicios del Paciente">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Productos y Servicios de {{ patient.name }}
      </h2>
    </template>

    <app-body>
      <div class="text-center pt-4">
        <div class="text-2xl">
          ${{ balance.balance }}
        </div>
        <div class="text-xl">
          Saldo Deudor
        </div>
      </div>
      <div class="mt-4 flex justify-center flex-wrap gap-4">
        <ui-button
          color="green"
          @click="$inertia.visit(route('patients.rates.create', patient.id))"
        >Añadir un Producto / Servicio</ui-button>

        <ui-button
          color="green"
          @click="$inertia.visit(route('patients.rates.payments.create', patient.id))"
        >Añadir un nuevo pago</ui-button>
      </div>
      <div class="mt-2 text-center grid grid-cols-2">
        <div class="">
          ${{ balance.debts }} en deudas
        </div>
        <div class="">
          ${{ balance.payments }} en pagos
        </div>
      </div>

      <div class="grid lg:grid-cols-2">

        <!-- Debts -->
        <div class="border-t mt-4 p-2 overflow-x-auto border-top">

          <div class="text-xl text-center">Cargos al cliente</div>

          <table class="w-full text-left mt-4">
            <tr>
              <th>Fecha</th>
              <th>Nombre</th>
              <th>Precio</th>
              <th>Total</th>
              <th></th>
            </tr>
            <tr
              v-for="rate, id in patient.rates"
              :key="id"
            >
              <td class="py-4">{{ rate.created_at }}</td>
              <td class="py-4">{{ rate.name }}</td>
              <td class="py-4">{{ rate.qty }} x ${{ rate.price }}</td>
              <td class="py-4">${{ rate.price * rate.qty }}</td>
              <td class="py-4">
                <ui-button
                  color="red"
                  @click="destroyRate(rate.id)"
                >Eliminar</ui-button>
              </td>
            </tr>
          </table>
        </div>

        <!-- Payments -->
        <div class="border-t lg:border-l mt-4 p-2 overflow-x-auto border-top">

          <div class="text-xl text-center">Abonos del cliente</div>

          <table class="w-full text-left mt-4">
            <tr>
              <th>Fecha</th>
              <th>Cantidad</th>
              <th>Concepto</th>
            </tr>
            <tr
              v-for="payment, id in patient.payments"
              :key="id"
            >
              <td class="py-4">{{ payment.created_at }}</td>
              <td
                class="py-4"
                :class="{'text-red-600': payment.ammount < 0}"
              >${{ payment.ammount }} - {{ payment.payment_method }}</td>
              <td class="py-4 italic">{{ payment.concept }}</td>
            </tr>
          </table>
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
      //
    };
  },

  methods: {
    destroyRate(id) {
      if (
        confirm("Estas seguro?") &&
        confirm("Esta accion no se puede deshacer")
      ) {
        const url = route("patients.rates.destroy", id);
        this.$inertia.delete(url);
      }
    },
  },
};
</script>
