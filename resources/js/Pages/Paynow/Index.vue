<template>
  <div class="pt-4 px-2">
    <div class="text-center">
      <div class="flex justify-center">
        <img
          src="/assets/logo.png"
          alt=""
        >
      </div>

      <div class="mt-4 text-xl">
        Hola <span class="text-2xl lowercase">{{ name }}</span>,
      </div>
      <div class="text-lg mt-4">
        Estás a punto de abonar <span class="text-2xl">${{ parseFloat(amount).toFixed(2) }}</span> a tu cuenta.
      </div>
      <div class="mt-4">
        <ui-button
          color="green"
          @click="pay"
        >
          Pagar Ahora
        </ui-button>
      </div>
    </div>
  </div>
</template>


<script>
import UiButton from "@/Shared/Frontend/Button";

export default {
  props: ["id", "name", "amount"],

  components: {
    UiButton,
  },

  methods: {
    pay() {
      const url = route("paynow.store", this.id);
      this.$inertia.post(url, { amount: this.amount });
    },
  },
};
</script>
