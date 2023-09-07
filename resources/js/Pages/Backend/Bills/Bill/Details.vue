<template>
  <app-layout title="Transacciones Fisiosalud">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        <span
          class="underline cursor-pointer"
          @click="$inertia.visit(route('bills.index'))"
          >Transacciones</span
        >
        <i class="mx-2 fas fa-angle-right"></i>
        Detalles
      </h2>
    </template>
    <br />
    <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8 bg-white text-center">
      <div class="grid grid-cols-1 sm:grid-cols-6 md:grid-cols-6 gap-4">
        <div class="col-span-4 shadow-md">
          <h2 class="font-bold text-xl mb-2 uppercase">
            {{ bill.bills_sub_family.billsfamily.name }}/{{
              bill.bills_sub_family.name
            }}
            - S/ {{ bill.quantity.toLocaleString() }}
          </h2>
          <div v-if="bill.status != 1">
            <button
              type="button"
              class="pb-2 mr-2 mb-2 inline-block px-2 py-2 bg-green-600 text-white font-medium text-xs leading-tight uppercase rounded shadow-md hover:bg-gray-900 hover:shadow-lg focus:bg-gray-900 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-gray-900 active:shadow-lg transition duration-150 ease-in-out"
              @click="approve(bill.id)"
            >
              Aprobar
              <i class="fa-sharp fa-solid fa-circle-check"></i>
            </button>
            <button
              type="button"
              class="pb-2 mb-2 inline-block px-2 py-2 bg-red-600 text-white font-medium text-xs leading-tight uppercase rounded shadow-md hover:bg-gray-900 hover:shadow-lg focus:bg-gray-900 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-gray-900 active:shadow-lg transition duration-150 ease-in-out"
              @click="deny(bill.id)"
            >
              Denegar
              <i class="fa-solid fa-circle-xmark"></i>
            </button>
          </div>
          <hr />
          <div class="overflow-x-auto relative shadow-md sm:rounded-lg">
            <div class="text-left py-2 px-2">
              <h3 class="font-bold text-md mb-2 uppercase">SOLICITADO</h3>
              <div class="solicitant">
                <span class="font-bold text-sm"> POR: </span>
                {{ bill.created_by }}
                <br />
                <span class="font-bold text-sm">EL </span>
                {{ dates.dateForHumans(bill.created_at) }}
                <span class="font-bold text-sm">A LAS</span>
                {{ dates.hourForHumans(bill.created_at) }}
              </div>
            </div>
          </div>
          <br />
          <div class="overflow-x-auto relative shadow-md sm:rounded-lg">
            <div class="text-left py-2 px-2">
              <h3 class="font-bold text-md mb-2 uppercase">
                RECIBE S/. {{ bill.quantity }}
              </h3>
              <div class="solicitant">
                {{ bill.receiver }}
                <span class="font-bold text-sm"> POR EL CONCEPTO DE</span>
                {{ bill.description }}
              </div>
            </div>
          </div>
          <br />
          <div class="overflow-x-auto relative shadow-md sm:rounded-lg">
            <div class="text-left py-2 px-2">
              <h3 class="font-bold text-md mb-2 uppercase">INFORMACIÓN DE PAGO:</h3>
              <div class="solicitant">
                <span class="font-bold text-sm"> Método de Pago:</span>
                {{ bill.paymentway }}
                <br />
                <span class="font-bold text-sm"> Origen del Dinero:</span>
                {{ bill.moneyOrigin }}
              </div>
            </div>
          </div>
        </div>

        <div class="col-span-2 shadow-md">
          <h2 class="font-bold text-xl mb-2 uppercase">
            <span v-if="bill.status == 0">PENDIENTE</span>
            <span v-else-if="bill.status == 1">APROBADO</span>
            <span v-else>DENEGADO</span>
          </h2>
          <h2 class="font-bold text-md mb-2 uppercase">
            NIVEL DE APROBACIÓN: <span v-if="bill.isDoubleChecked">2</span>
            <span v-else class="font-light text-xl">1</span>
          </h2>
          <hr />

          <br />

          <div
            v-if="bill.isApproved"
            class="overflow-x-auto relative shadow-md sm:rounded-lg bg-green-100 py-2"
            v-bind:class="
              bill.isApproved == 0
                ? 'bg-gray-100'
                : bill.isApproved == 'SI'
                ? 'bg-green-100'
                : 'bg-red-100'
            "
          >
            <span class="font-bold text-md mb-2 uppercase">
              {{ bill.approved_by }}
            </span>
            <br />
            <span class="tx-white-100"> {{ bill.approved_at }} </span>
          </div>

          <div
            v-else
            class="overflow-x-auto relative shadow-md sm:rounded-lg bg-gray-100 py-2"
          >
            <span class="font-bold text-md mb-2 uppercase"> PENDIENTE </span>
            <br />
            <span class="tx-white-100"> a la espera de aprobación </span>
          </div>

          <br />

          <div v-if="bill.isDoubleChecked">
            <div
              v-if="bill.secondIsApproved"
              class="overflow-x-auto relative shadow-md sm:rounded-lg bg-green-100 py-2"
            >
              <span class="font-bold text-md mb-2 uppercase">
                {{ bill.second_approved_by }}
              </span>
              <br />
              <span class="tx-white-100"> {{ bill.second_approved_at }} </span>
            </div>
            <div
              v-else
              class="overflow-x-auto relative shadow-md sm:rounded-lg bg-gray-100 py-2"
            >
              <span class="font-bold text-md mb-2 uppercase"> PENDIENTE </span>
              <br />
              <span class="tx-white-100"> a la espera de aprobación </span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </app-layout>
</template>
<script>
import AppLayout from "@/Layouts/AppLayout.vue";
import JetSectionBorder from "@/Jetstream/SectionBorder.vue";
import dates from "@/ui/dates.js";
import axios from "axios";

export default {
  props: ["bill"],
  components: {
    AppLayout,
    JetSectionBorder,
  },
  data() {
    return {
      bill: this.bill,
    };
  },
  setup() {
    return { dates };
  },
  mounted() {},
  methods: {
    approve(id) {
      axios
        .post(`/dashboard/approveBill/${id}`)
        .then((response) => {
          window.location.reload();
        })
        .catch((error) => {
          console.error(error);
        });
    },
    deny(id) {
      axios
        .post(`/dashboard/denyBill/${id}`)
        .then((response) => {
          window.location.reload();
        })
        .catch((error) => {
          console.error(error);
        });
    },
  },
};
</script>
