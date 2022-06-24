<template>
  <div>
    <jet-form-section @submitted="onSubmitted">
      <template #title>
        Añadir resultado
      </template>

      <template #form>
        <!-- name -->
        <form-input
          label="Resultado"
          name="result"
          v-model="form.result"
          :form="form"
        />

        <!-- interpretation -->
        <form-input
          label="Interpretación"
          name="interpretation"
          v-model="form.interpretation"
          :form="form"
        />

        <!-- certificate -->
        <form-input
          label="Certificado"
          name="certificate"
          v-model="form.certificate"
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
  props: ["model"],

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

        result: null,
        interpretation: null,
        certificate: null,

        ...this.model,
      }),
    };
  },

  methods: {
    onSubmitted() {
      const model = this.model;

      const url = route("testTypes.addResult", model.id);

      this.form.post(url, {
        // errorBag: "",
        preserveScroll: true,
        onSuccess: this.onSuccess,
      });
    },

    destroy() {
      if (
        confirm(
          "Esta acción no puede deshacerse, presione cancelar si no está seguro."
        )
      ) {
        this.$inertia.delete(route("testTypes.destroy", this.form.id));
      }
    },
  },
};
</script>