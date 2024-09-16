<template>
  <layout>
    <ui-container>
      <!-- Head -->
      <div class="">
        <div class="text-4xl font-bold" style="text-align:center; color: cadetblue;font-size:1.2rem">ELIGE TUS TARIFAS</div>
        <div class="flex items-center gap-4" style="justify-content:center">
          <div class="text-lg text-gray-600" style="
              font-weight: bold;
              margin-top: 0.5rem;
              text-align:center;
              font-size: 0.9rem;
          ">
  
          Por favor, para agilizar tu ingreso y registro <b>elige tus tarifas aquí</b>

          </div>
        </div>
      </div>

      <div class="container" style="margin-top:1rem;">
        <div class="text-4xl font-bold" style="text-align:center; color: cadetblue; font-size:1.55rem; font-weight:bolder">Tarifas</div>
      </div>
      <!-- @TODO Acá podrás ver las tarifas disponibles para el paciente -->
      <div class="container" style="">
        <link-group style="display: block!important;">
          <link-item 
            class="mt-2rem"
            label="Evaluación + 1ra Sesión de Fisioterapia (150 soles)"
            @click="go(288)"
          />
          <link-item 
            class="mt-2rem"
            label="Descarga Muscular x 75 minutos (120 soles)"
            @click="go(10)"
          />
          <link-item 
            class="mt-2rem"
            label="Consulta Médica - Doctor Fisiatra (200 soles)"
            @click="go(241)"
          />
          <link-item 
            class="mt-2rem"
            label="Evaluación Ortopédica (plantillas) (90 soles)"
            @click="go(178)"
          />
          <link-item 
            class="mt-2rem"
            label="Masaje Descontracturante Antiestrés (120 soles)"
            @click="go(58)"
          />
          <link-item 
            class="mt-2rem"
            label="Ondas de Choque x 1 sesión (190 soles)"
            @click="go(244)"
          />
          <link-item 
            class="mt-2rem"
            label="Ondas de Choque x 2 sesiones (180 c/u) (360 soles)"
            @click="go(245)"
          />
          <link-item 
            class="mt-2rem"
            label="Ondas de Choque x 3 sesiones (170 c/u) (510 soles)"
            @click="go(247)"
          />
          <link-item 
            class="mt-2rem"
            label="Ondas de Choque x 4 sesiones (160 c/u) (640 soles)"
            @click="go(248)"
          />
          <link-item 
            class="mt-2rem"
            label="Ondas de Choque x 5 sesiones (150 c/u) (750 soles)"
            @click="go(259)"
          />
        </link-group>
      </div>

      <div>
        <link-item 
          style="color:blue"
          class="mt-2rem"
          label="Volver al Menú"
          icon="fa-solid fa-arrow-left"
          @click="menu()"
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

export default {
  props: ["model"],

  components: {
    Layout,
    UiContainer,
    UiButton,

    LinkGroup,
    LinkItem,
  },

   methods: {
    go(rateId) {
      const url = route('area.patients.addRatePatient', rateId);
      this.$inertia.visit(url);
    },
    menu() {
      const url = route('area.patients.index');
      this.$inertia.visit(url);
    }
  },
};
</script>

