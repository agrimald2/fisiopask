<template>
  <layout>
    <ui-container>
      <!-- Message -->

      <!-- Appointment card -->
      <div class="flex justify-center">
        <div class="mt-2 border py-2 px-8 inline-block rounded-xl text-center">
          Cita para el
          <div class="capitalize text-xl">{{ dates.dateForHumans(model.date) }}</div>
          <div class="">
            de
            <span
              class="capitalize text-xl"
              v-text="model.start"
            ></span>
            a
            <span
              class="capitalize text-xl"
              v-text="model.end"
            ></span>
            hrs.
          </div>
        </div>
      </div>

      <!-- Warning text -->
      <div class="mt-2 text-lg text-gray-600">
        Esta acción no se puede deshacer y una vez canceles la cita tendrás que volver a agendarla.
      </div>

      <!-- Buttons -->
      <div class="mt-4">
        <div class="grid items-center gap-4">

          <!-- Cancel
          <ui-button
            color="red"
            @click="confirm"
          >
            Cancelar mi Cita
          </ui-button>
          -->
          <!-- Logout -->
          <ui-button
            color="yellow"
            @click="rebook"
          >
            Reprogramar 
          </ui-button>

          <!-- Back -->
          <ui-button
            color="green"
            @click="$inertia.visit(route('area.patients.index'))"
          >
            Volver atrás
          </ui-button>
        </div>
      </div>

    </ui-container>
  </layout>
</template>

<script>
import Layout from "../Layout/Layout";
import UiContainer from "@/Pages/Frontend/BookAppointment/UI/Container";
import UiButton from "@/Shared/Frontend/Button";

import dates from "@/ui/dates.js";

export default {
  props: ["model", "url", "urlRebook"],

  components: {
    Layout,
    UiContainer,
    UiButton,
  },

  setup() {
    return { dates };
  },

  methods: {
    confirm() {
      this.$inertia.post(this.url);
    },

    rebook() {
      this.$inertia.post(this.urlRebook);
    },
  },
};
</script>
