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
              @click="goHome"
            >Volver al Inicio</ui-button>
          </div>
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
  },
};
</script>
