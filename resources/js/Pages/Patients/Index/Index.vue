<template>
  <layout>
    <ui-container>
      <!-- Head -->
      <div class="">
        <div class="text-4xl font-bold">{{ model.name }}</div>
        <div class="flex items-center gap-4">
          <div class="text-lg text-gray-600">{{ model.lastname1 }} {{ model.lastname2 }}</div>
          <div class="text-sm text-gray-400">{{ model.birth_date }}</div>
        </div>
      </div>

      <div class="mt-4">
        <div class="flex flex-wrap justify-between items-center gap-4">
          <!-- Logout -->
          <ui-button
            color="red"
            @click="$inertia.visit(route('area.patients.logout'))"
          >
            Cerrar Sesión
          </ui-button>

          <!-- Rebook -->
          <a
            :href="route('area.patients.rebook')"
            target="_blank"
          >
            <ui-button color="green">
              Agendar una nueva cita
            </ui-button>
          </a>
        </div>
      </div>

      <!-- Ver Pruebas -->
      <div class="mt-6">
        <div class="text-4xl font-bold">Últimas Pruebas</div>
        <div class="mt-4">
          <TestsCmp :tests="tests"/>
        </div>
      </div>

      <div v-if="appointments.length > 0">
        <!-- Ver Tarifas -->
        <div class="mt-6">
          <div class="text-4xl font-bold">Tarifas Activas</div>
          <div class="mt-4">
            <RatesCmp :rates="rates"/>
          </div>
        </div>

        <!-- Ver citas -->
        <div class="mt-6">
          <div class="text-4xl font-bold">Citas Pendientes</div>
          <div class="mt-4">
            <appointments :appointments="appointments" :past="false"/>
          </div>
        </div>
        <div class="mt-6">
          <div class="text-4xl font-bold">Citas Pasadas</div>
          <div class="mt-4">
            <appointments :appointments="appointments" :past="true" />
          </div>
        </div>
      </div>

    </ui-container>
  </layout>
</template>

<script>
import Layout from "../Layout/Layout";
import UiContainer from "@/Pages/Frontend/BookAppointment/UI/Container";
import UiButton from "@/Shared/Frontend/Button";

import Appointments from "./Components/Appointments";
import RatesCmp from "./Components/Rates";
import TestsCmp from "./Components/Tests";

export default {
  props: ["model", "appointments", "rates", "tests"],

  components: {
    Layout,
    UiContainer,
    UiButton,

    Appointments,
    RatesCmp,
    TestsCmp,
  },
};
//TODO @ IMPROVE CLIENT DESIGN
</script>

