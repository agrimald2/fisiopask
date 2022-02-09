<template>
  <div>
    <jet-form-section @submitted="onSubmitted">
      <template #title>
        Buscar un Paciente
      </template>

      <template #form>
        <form-input
          label="DNI"
          name="dni"
          v-model="form.dni"
          type="text"
          :form="form"
        />
      </template>

      <template #actions>
        <jet-action-message
          :on="form.recentlySuccessful"
          class="mr-3"
        >
          Guardado.
        </jet-action-message>

        <jet-button
          :class="{ 'opacity-25': form.processing }"
          :disabled="form.processing"
        >
          Guardar
        </jet-button>
      </template>
    </jet-form-section>
  </div>
</template>

<script>
import JetButton from "@/Jetstream/Button.vue";
import JetDangerButton from "@/Jetstream/DangerButton.vue";
import JetFormSection from "@/Jetstream/FormSection.vue";
import JetActionMessage from "@/Jetstream/ActionMessage.vue";
import JetSecondaryButton from "@/Jetstream/SecondaryButton.vue";

import FormInput from "@/Shared/Backend/Form/Input";

export default {
  components: {
    JetActionMessage,
    JetButton,
    JetFormSection,
    JetSecondaryButton,

    JetDangerButton,

    FormInput,
  },

  data() {
    return {
      form: this.$inertia.form({
        _method: "POST",

        dni: null,
      }),
    };
  },
  
  methods: {
    onSubmitted() {
      let url = route("tests.checkDNI");

      this.form.post(url, {
        preserveScroll: true,
        onSuccess: this.onSuccess,
      });
    },
  },
};
</script>

<style scoped>
  .cb{
    width:5vw; height:5vw
  }
</style>