<template>
  <layout>
    <ui-container>
      <!-- Head -->
      <div class="">
        <div class="text-4xl font-bold" style="text-align:center; color: cadetblue;font-size:1.5rem">DALE CLICK A LA CITA QUE DESEAS REPROGRAMAR</div>
        <div class="flex items-center gap-4" style="justify-content:center">
          <div class="text-lg text-gray-600" style="
              font-weight: bold;
              margin-top: 1rem;
              text-align:center;
              font-size: 0.9rem;
              color:black;
          ">
  
          Recuerda que podría ser que no encuentres a tu mismo Licenciado en la nueva fecha y hora que quieres. Si eso pasa igual podremos atenderte con otro Licenciado.

          </div>
        </div>
      </div>

      <div class="container" style="">
          <appointments :appointments="filteredAppointments" :past="false"/>
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
  data(){
    return {
      dateTime: new Date(),
    }
  },
  computed: {
    filteredAppointments() {
      const thresholdTime = this.dateTime.getTime() + (5 * 60 * 60 * 1000); // 5 Hours from current time in milliseconds
      return this.appointments.filter(appointment => {
        const appointmentTime = new Date(`${appointment.date}T${appointment.start}`).getTime();
        return appointmentTime >= thresholdTime;
      });
    }
  },
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

