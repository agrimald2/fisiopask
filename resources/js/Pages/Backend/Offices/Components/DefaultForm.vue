<template>
  <jet-form-section @submitted="onSubmitted">
    <template #title>
      {{ model ? 'Editar' : 'Crear' }} una Sucursal
    </template>

    <template #description>
      Cada doctor puede trabajar en múltiples sucursales.
    </template>

    <template #form>
      <!-- name -->
      <form-input
        label="Nombre"
        name="name"
        v-model="form.name"
        :form="form"
      />

      <!-- address -->
      <form-input
        label="Dirección"
        name="address"
        v-model="form.address"
        type="text"
        :form="form"
      />

       <!-- reference -->
      <form-input
        label="Referencia"
        name="reference"
        v-model="form.reference"
        type="text"
        :form="form"
      />

       <!-- indications -->
      <form-input
        label="Indicaciones"
        name="indications"
        v-model="form.indications"
        type="text"
        :form="form"
      />
       <!-- maps_link -->
      <form-input
        label="Link de Google Maps"
        name="maps_link"
        v-model="form.maps_link"
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
</template>

<script>
import JetButton from "@/Jetstream/Button.vue";
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

    FormInput,
  },

  data() {
    return {
      form: this.$inertia.form({
        _method: "POST",

        name: null,
        address: null,
        reference: null,
        maps_link: null,
        indications: null,

        ...this.model,
      }),
    };
  },

  methods: {
    onSubmitted() {
      if (this.model) {
        this.submitEditForm();
      } else {
        this.submitCreateForm();
      }
    },

    submitEditForm() {
      const url = route("offices.update", this.model.id);

      this.form._method = "PUT";

      this.form.post(url, {
        // errorBag: "",
        preserveScroll: true,
        onSuccess: this.onSuccess,
      });
    },

    submitCreateForm() {
      const url = route("offices.store");

      this.form._method = "POST";

      this.form.post(url, {
        // errorBag: "",
        preserveScroll: true,
        onSuccess: this.onSuccess,
      });
    },

    onSuccess() {
      // Clear inputs
    },
  },
};
</script>
