<template>
  <layout>
    <ui-container>
      <!-- Head -->
      <div class="">
        <div class="text-4xl font-bold" style="text-align:center; color: cadetblue;font-size:1.5rem">CITAS PROGRAMADAS</div>
      </div>
      <div class="container" style="">
          <appointments :appointments="appointments" :past="false"/>
      </div>
      <div>
        <p style="text-align: center;
    font-weight: 900;
    color: crimson;">
          Reserva siempre el horario de tu Licenciado programando por lo menos 4 Citas
        </p>
      </div>
      <div>
        <link-item 
          style="color:blue"
          class="mt-2rem"
          label="Agendar Nueva Cita"
          icon="fa-solid fa-calendar"
          @click="go('area.patients.rebook')"
        />
        <link-item 
          style="color:blue"
          class="mt-2rem"
          label="Ver Citas Pasadas"
          icon="fa-solid fa-paste"
          @click="go('area.patients.appointments.past')"
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
    margin-top:1.4rem;
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

  mounted (){
    console.log(this.appointments);
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

