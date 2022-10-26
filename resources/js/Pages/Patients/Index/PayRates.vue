<template>
  <layout>
    <ui-container>
      <!-- Head -->
      <div class="">
        <div class="text-4xl font-bold" style="text-align:center; color: cadetblue;font-size:1.2rem">PAGA TUS CITAS CON ANTICIPACIÓN</div>
        <div class="flex items-center gap-4" style="justify-content:center">
          <div class="text-lg text-gray-600" style="
              font-weight: bold;
              margin-top: 0.5rem;
              text-align:center;
              font-size: 0.9rem;
          ">

          Evita colas y esperas en Recepción pagando con anticipación tus Citas pendientes.

          </div>
        </div>
      </div>

      <div class="container" style="margin-top:1rem;">
        <div class="text-4xl font-bold" style="text-align:center; color: cadetblue; font-size:1.55rem; font-weight:bolder">Tarifas Activas</div>
      </div>

      <div class="container" style="">
          <RatesCmp :rates="rates"/>
          <div v-if="rates.length <=0">
            <strong style="text-align:center;">
                No tienes tarifas activas, por favor añade una dando click en "Añadir Tarifas"
            </strong>
          </div>
      </div>

      <div>
        <link-item
          v-if="rates.length <=0"
          style="color:blue"
          class="mt-2rem"
          label="Añadir Tarifas"
          icon="fa-clipboard-list"
          @click="go('area.patients.addRatesSelf')"
        />
        <link-item
          style="color:blue"
          class="mt-2rem"
          label="Volver al Menú"
          icon="fa-solid fa-arrow-left"
          @click="go('area.patients.index')"
        />
      </div>
    </ui-container>
  </layout>
</template>

<style scoped>
  .mt-2rem{
    margin-top:1rem;
  }
  .container .grid{
    display: block!important;
  }
</style>
<script>
import LinkGroup from "@/Shared/Backend/Links/GroupBlock";
import LinkItem from "@/Shared/Backend/Links/Item";
import Layout from "../Layout/Layout";
import UiContainer from "@/Pages/Frontend/BookAppointment/UI/Container";
import UiButton from "@/Shared/Frontend/Button";

import Appointments from "./Components/Appointments";
import RatesCmp from "./Components/Rates";

export default {
  props: ["model", "appointments", "rates"],

  components: {
    Layout,
    UiContainer,
    UiButton,

    LinkGroup,
    LinkItem,

    Appointments,
    RatesCmp,
  },

   methods: {
    go(routeName) {
      const url = route(routeName);
      this.$inertia.visit(url);
    },
  },
};
//TODO @ IMPROVE CLIENT DESIGN
</script>

