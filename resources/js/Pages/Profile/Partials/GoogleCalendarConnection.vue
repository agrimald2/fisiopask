<template>
  <jet-action-section>
    <template #title>
      Conectar cuenta con Google Calendar
    </template>

    <template #description>
      Conecta tu cuenta con google calendar.
    </template>

    <template #content>
      <div class="max-w-xl text-sm text-gray-600">
        Automáticamente crea todas las citas generadas desde la aplicación con tu calendario de Google.
      </div>

      <div class="mt-5">
        <div class="text-center">
          <ui-button
            color="blue"
            @click="connectWithGoogle"
            v-show="!$inertia.page.props.google_calendar"
          >
            Conectar con Google
          </ui-button>

          <div v-show="$inertia.page.props.google_calendar">
            <div class="inline-block text-sm text-white bg-green-400 px-2 py-1 rounded">
              <i class="mr-2 fas fa-check"></i>
              Conectado con éxito
            </div>
            <div class="pt-2"></div>
            <ui-button
              color="red"
              @click="disconnectFromGoogle"
            >
              Desconectar de Google
            </ui-button>
          </div>
        </div>
      </div>
    </template>
  </jet-action-section>
</template>

<script>
import JetActionSection from "@/Jetstream/ActionSection.vue";
import JetDialogModal from "@/Jetstream/DialogModal.vue";
import JetDangerButton from "@/Jetstream/DangerButton.vue";
import JetInput from "@/Jetstream/Input.vue";
import JetInputError from "@/Jetstream/InputError.vue";
import JetSecondaryButton from "@/Jetstream/SecondaryButton.vue";
import UiButton from "@/Shared/Frontend/Button";

export default {
  components: {
    JetActionSection,
    JetDangerButton,
    JetDialogModal,
    JetInput,
    JetInputError,
    JetSecondaryButton,
    UiButton,
  },

  data() {
    return {
      //
    };
  },

  methods: {
    connectWithGoogle() {
      const url = route("google.calendar.connect");
      this.$inertia.post(url);
    },

    disconnectFromGoogle() {
      const url = route("google.calendar.disconnect");
      this.$inertia.post(url);
    },
  },
};
</script>
