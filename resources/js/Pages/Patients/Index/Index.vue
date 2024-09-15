<template>
  <layout>
    <ui-container>
      <!-- Head -->
      <div class="">
        <div class="text-4xl font-bold" style="text-align:center; color: cadetblue;">Bienvenid@!</div>
        <div class="flex items-center gap-4" style="justify-content:center">
          <div class="text-lg text-gray-600" style="
              font-weight: bold;
              margin-top: 1rem;
              text-align:center;
              font-size: 1.2rem;
          ">

            {{ model.name }} {{ model.lastname1 }} {{ model.lastname2 }} <br>

          </div>
        </div>
      </div>

      <div class="container" style="margin-top:2rem">
        <div class="text-4xl font-bold"
          style="text-align:center; color: #12b98aeb; font-size:1.55rem; font-weight:bolder">¿Qué acción desea realizar?
        </div>
        <p style="text-align:center">Dale click en una acción</p>
        <i class="fas fa-angle-down mx-4" style="justify-content: center; display: flex;font-size: 2rem;"></i>
      </div>

      <div class="container" style="margin-top: -2rem;">
        <link-group style="display: block!important;">

          <link-item class="mt-2rem" label="Añadir Tarifa Para Pago" icon="fa-clipboard-list"
            @click="go('area.patients.addRatesSelf')" />

          <link-item class="mt-2rem" label="Pagar Mis Citas" icon="fa-solid fa-money-bill"
            @click="go('area.patients.rates')" />

          <link-item class="mt-2rem" label="Agendar Nuevas Citas" icon="fa-solid fa-calendar"
            @click="go('area.patients.rebook')" />

          <link-item class="mt-2rem" label="Ver Lista de Citas" icon="fa-solid fa-list"
            @click="go('area.patients.appointments')" />

          <link-item class="mt-2rem" label="Reprogramar Citas" icon="fa-solid fa-calendar-day"
            @click="go('area.patients.appointments.reprogram')" />

          <!--
            <link-item
              class="mt-2rem"
              label="Cancelar Sin Reprogramar"
              icon="fa-solid fa-calendar-minus"
              @click="go('area.patients.appointments.cancel')"
            />
          -->

        </link-group>
      </div>
      <div class="container" style="justify-content:center;display:flex">
        <ui-button color="red" @click="go('area.patients.logout')">
          Cerrar Sesión
        </ui-button>
      </div>
    </ui-container>
  </layout>
</template>

<style scoped>
.mt-2rem {
  margin-top: 1rem;
  font-size: 1.3rem;
}

.mt-3rem {
  margin-top: 2rem;
  font-size: 1.3rem;
}

.container .grid {
  display: block !important;
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
