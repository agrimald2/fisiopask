<template>
  <app-layout title="Toasty">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Toasty
      </h2>
    </template>

    <app-body>

      <div class="py-12 px-4">
        <jet-button @click="toast">Toast!</jet-button>

        <br>
        <br>
        <jet-button @click="confirm">Modal!</jet-button>
        <br>

        <confirmation-modal ref='modal'>
          Por favor confirma hijueputa.
        </confirmation-modal>
      </div>

    </app-body>

  </app-layout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout.vue";
import AppBody from "@/Shared/Backend/AppBody";
import JetButton from "@/Jetstream/Button";

import ConfirmationModal from "@/Shared/Modal/ConfirmationModal";

import notify from "@/ui/notify";

export default {
  props: [],

  components: {
    AppLayout,
    AppBody,

    JetButton,

    ConfirmationModal,
  },

  data() {
    return {
      //
    };
  },

  methods: {
    toast() {
      notify.danger("Producto creado con éxito!");
    },
    confirm() {
      this.$refs.modal.show(
        () => notify.success("thank you for your confirmation!"),
        () => notify.danger("You did not confirm!")
      );
    },
  },
};
</script>
