<template>
  <layout>
    <ui-container>
      <!-- Head -->
      <div class="">
        <div class="text-4xl font-bold" style="text-align:center; color: cadetblue;font-size:1.5rem">CANCELAR CITAS</div>
        <div class="flex items-center gap-4" style="justify-content:center">
          <div class="text-lg text-gray-600" style="
              font-weight: bold;
              margin-top: 1rem;
              text-align:center;
              font-size: 0.9rem;
              color:black;
          ">
  
          Selecciona la cita que deseas eliminar

          </div>
        </div>
      </div>
      <div class="container" style="">
          <appointments :appointments="appointments" :past="false"/>
      </div>
      <div>
      </div>
      <div>
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

