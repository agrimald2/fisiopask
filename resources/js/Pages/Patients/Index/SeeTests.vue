<template>
  <layout>
    <ui-container>
      <!-- Head -->
      <div class="">
        <div class="text-4xl font-bold" style="text-align:center; color: cadetblue;font-size:1.5rem">EXÁMENES DE LABOTORIO</div>
      </div>
      <div class="container" style="">
        <TestsCmp :tests="tests"/>
      </div>
      <div>
        <p style="text-align: center;
    font-weight: 900;
    color: crimson;">
          Si tienes alguna consulta, no dudes en contactarnos
        </p>
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
import TestsCmp from "./Components/Tests";


export default {
  props: ["model", "appointments", "rates", "tests"],

  components: {
    Layout,
    UiContainer,
    UiButton,

    LinkGroup,
    LinkItem,

    Appointments,
    RatesCmp,
    TestsCmp,
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

