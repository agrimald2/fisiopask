<template>
  <app-layout title="Transacciones">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        <div class="flex items-center gap-4">
          <span
            class="underline cursor-pointer"
            @click="$inertia.visit(route('bills.index'))"
            >Cubículos</span
          >
          <i class="fas fa-angle-right"></i>
          {{ model ? "Editar" : "Crear" }} una Transacción
        </div>
      </h2>
    </template>

    <div>
      <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
        <!-- Default form -->
        <DefaultForm
          :model="model"
          :subfamilies="subfamiliesOptions"
          :origins="originsOptions"
          :payers="payersOptions"
          :paymentMethods="paymentMethodsOptions"
          :receivers="receiversOptions"
          class="mt-10 sm:mt-0"
        />

        <JetSectionBorder />

        <div v-if="model" class="text-center px-4 mt-6">
          <FrontButton color="red" @click="deleteWorkspace">
            Eliminar este Cubículo
          </FrontButton>
        </div>
      </div>
    </div>
  </app-layout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout.vue";
import JetSectionBorder from "@/Jetstream/SectionBorder.vue";

import FrontButton from "@/Shared/Frontend/Button";

import DefaultForm from "./Components/DefaultForm";

export default {
  props: [
    "model",
    "subfamilies",
    "origins",
    "payers",
    "paymentMethods",
    "receivers",
  ],

  components: {
    AppLayout,

    JetSectionBorder,
    FrontButton,

    DefaultForm,
  },

  computed: {
    subfamiliesOptions() {
      let list = {};
      this.subfamilies.map((x) => {
        list[x.id] = x.name;
      });
      return list;
    },

    originsOptions() {
      let list = {};
      this.origins.map((x) => {
        list[x.id] = x.name;
      });
      return list;
    },

    payersOptions() {
      let list = {};
      this.payers.map((x) => {
        list[x.id] = x.name;
      });
      return list;
    },

    paymentMethodsOptions() {
      let list = {};
      this.paymentMethods.map((x) => {
        list[x.id] = x.name;
      });
      return list;
    },

    receiversOptions() {
      let list = {};
      this.receivers.map((x) => {
        list[x.id] = x.name;
      });
      return list;
    },
  },

  methods: {
    deleteWorkspace() {
      if (!confirm("Estas seguro?")) return;

      const url = route("workspaces.destroy", this.model.id);
      this.$inertia.delete(url);
    },
  },
};
</script>
