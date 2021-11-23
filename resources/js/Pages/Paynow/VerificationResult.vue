<template>
  <div class="pt-4 px-2">
    <div class="text-center">
      <div class="flex justify-center">
        <img
          src="/assets/logo.png"
          alt=""
        >
      </div>

      <div class="mt-4 text-2xl">
        <template v-if="isVerified">
          El pago ha sido aprobado.
        </template>
        <template v-else>
          El pago no ha sido aprobado.
        </template>
      </div>
      <div
        class="text-lg mt-2"
        v-if="message"
      >
        {{ message }}
      </div>
      <div class="mt-4">
        <ui-button
          color="green"
          @click="goHome"
        >
          Volver al inicio
        </ui-button>
      </div>
    </div>
  </div>
</template>


<script>
import UiButton from "@/Shared/Frontend/Button";

export default {
  props: ["isVerified", "homeUrl", "message"],

  components: {
    UiButton,
  },

  methods: {
    goHome() {
      this.$inertia.visit(this.homeUrl);
    },
  },
};
</script>
