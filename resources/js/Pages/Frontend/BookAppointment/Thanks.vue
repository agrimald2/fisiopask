<template>
  <Layout>
    <form @submit.prevent="onSubmit">
      <ui-container>
        <!-- Title -->
        <div class="text-center text-4xl uppercase tracking-wider text-gray-800">
          ¡Agendado!
        </div>

        <!-- Info -->
        <div class="mt-6 grid gap-4 text-center">
          <div class="text-lg text-gray-500 tracking-wide">
            Tu cita ha sido agendada con éxito. <br>
            <span v-if="phone">
              Recibirás una confirmación a Whatsapp:
            </span>
            
            <br>
            Si no te llegó la confirmación al WHATSAPP.

            <br>
            Por favor dale click al botón de abajo.
          </div>
          <!--
          <div
            class="text-2xl"
            v-if="phone"
          >
            +{{ phone }}
          </div>
          -->
          <div class="mt-2 grid">
            <ui-button
              type="button"
              @click="goWha"
            >RECIBIR CONFIRMACIÓN</ui-button>
          </div>
          <!--
          <div class="mt-2 grid">
            <ui-button
              color="yellow"
              @click="goHome"
            >Volver al Inicio</ui-button>
          </div> -->
          <a href="https://fisiosalud.pe" style="text-decoration: underline;">
            Volver al inicio
          </a>
        </div>

      </ui-container>
    </form>
  </Layout>
</template>


<script>
import Layout from "./Layout/Layout.vue";

import UiContainer from "./UI/Container.vue";
import UiButton from "./UI/Button.vue";

export default {
  props: ["phone", "buttonUrl"],

  components: {
    Layout,
    UiContainer,
    UiButton,
  },

  methods: {
    goHome() {
      window.location = this.buttonUrl;
    },
    goWha() {
        let whaUrl = 'https://api.whatsapp.com/send?phone='+this.phone+'&text=Hola%2C%0AAcabo%20de%20agendarme%20por%20la%20web.%0A%0AEspero%20mi%20CONFIRMACI%C3%93N%20de%20cita.%20%0A%0AMuchas%20gracias.'
        window.location = whaUrl;
    },
  },
};
</script>
