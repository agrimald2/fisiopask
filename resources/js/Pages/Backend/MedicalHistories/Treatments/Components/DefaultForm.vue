<template>
  <JetFormSection @submitted="onSubmitted">
    <template #title>
      {{ model ? 'Editar' : 'Crear' }} un Tratamiento
    </template>

    <template #form>
      <!-- Name -->
      <FormInput
        label="Nombre"
        name="name"
        v-model="form.name"
        :form="form"
      />

      <!-- Description -->
      <FormInput
        label="Descripción"
        name="description"
        v-model="form.description"
        type="text"
        :form="form"
      />

    </template>

    <template #actions>
      <JetActionMessage
        :on="form.recentlySuccessful"
        class="mr-3"
      >
        Guardado.
      </JetActionMessage>

      <JetButton
        :class="{ 'opacity-25': form.processing }"
        :disabled="form.processing"
      >
        Guardar
      </JetButton>
    </template>
  </JetFormSection>
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
        description: null,

        ...this.model,
      }),
    };
  },

  methods: {
    onSubmitted() {
      if(this.model) this.submitEditForm();
      else this.submitCreateForm();
    },

    submitEditForm() {
      const url = route("treatment.update", this.model.id);

      this.form._method = "PUT";

      this.form.post(url, {
        // errorBag: "",
        preserveScroll: true,
        onSuccess: this.onSuccess,
      });
    },

    submitCreateForm() {
      const url = route("treatment.store");

      this.form._method = "POST";

      this.form.post(url, {
        // errorBag: "",
        preserveScroll: true,
        onSuccess: this.onSuccess,
      });
    },
  },
};
</script>
