<template>
  <modal ref='modal'>
    <div class="border-b text-xl text-center tracking-wider bg-gray-800 text-white font-bold px-4 py-4">
      {{ title }}
    </div>

    <div class="px-4 py-6 text-center">
      <slot />
    </div>

    <div class="grid grid-cols-2 border-t">
      <div
        @click="cancel"
        class="bg-white hover:bg-gray-200 cursor-pointer text-center py-4"
      >
        Cancelar
      </div>
      <div
        @click="confirm"
        class="bg-red-500 text-white hover:bg-red-600 cursor-pointer text-center py-4"
      >
        Confirmar
      </div>
    </div>
  </modal>
</template>


<script>
import Modal from "./Modal";

export default {
  props: {
    title: { default: "Confirmación" },
  },

  emits: ["cancelled", "confirmed"],

  components: {
    Modal,
  },

  data() {
    return {
      callbackConfirm: null,
      callbackCancel: null,
    };
  },

  methods: {
    show(callbackConfirm = null, callbackCancel = null) {
      this.$refs.modal.show();
      this.callbackConfirm = callbackConfirm;
      this.callbackCancel = callbackCancel;
    },

    hide() {
      this.$refs.modal.hide();
    },

    cancel() {
      this.hide();
      this.$emit("cancelled");
      if (this.callbackCancel) {
        this.callbackCancel();
      }
    },

    confirm() {
      this.hide();
      this.$emit("confirmed");
      if (this.callbackConfirm) {
        this.callbackConfirm();
      }
    },
  },
};
</script>
