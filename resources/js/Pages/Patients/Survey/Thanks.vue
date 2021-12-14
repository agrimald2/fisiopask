<template>
  <Layout>
    <form @submit.prevent="onSubmit">
      <ui-container>
        <!-- Title -->
        <div class="text-center text-4xl uppercase tracking-wider text-gray-800">
          ¡Gracias por tu feedback!
        </div>

        <!-- Info -->
        <div class="mt-6 grid gap-4 text-center">
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
import Layout from "@/Pages/Frontend/BookAppointment/Layout/Layout.vue";

import UiContainer from "@/Pages/Frontend/BookAppointment/UI/Container.vue";
import UiButton from "@/Pages/Frontend/BookAppointment/UI/Button.vue";
import { Inertia } from '@inertiajs/inertia';

export default {
  components: {
    Layout,
    UiContainer,
    UiButton,
  },

  methods: {
    goHome() {
        Inertia.visit(route('area.patients.index'));
    },
  },
};
</script>
