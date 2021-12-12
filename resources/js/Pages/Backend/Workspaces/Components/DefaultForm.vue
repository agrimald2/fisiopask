<template>
  <JetFormSection @submitted="onSubmitted">
    <template #title>
      {{ model ? 'Editar' : 'Crear' }} un Cubículo
    </template>

    <template #form>
      <!-- name -->
      <FormInput
        label="Nombre"
        name="name"
        v-model="form.name"
        :form="form"
      />

      <!-- description -->
      <FormInput
        label="Descripción"
        name="description"
        v-model="form.description"
        type="text"
        :form="form"
      />

      <!-- office -->
      <FormInput
        label="Sucursal"
        name="office"
        v-model="form.office_id"
        type="select"
        :options="offices"
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
  props: ["model", "offices"],

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
        office_id: null,

        ...this.model,
      }),
    };
  },

  methods: {
    onSubmitted() {
      if(this.form.description == null) this.form.description = "";
      if (this.model) {
        this.submitEditForm();
      } else {
        this.submitCreateForm();
      }
    },

    submitEditForm() {
      const url = route("workspaces.update", this.model.id);

      this.form._method = "PUT";

      this.form.post(url, {
        // errorBag: "",
        preserveScroll: true,
        onSuccess: this.onSuccess,
      });
    },

    submitCreateForm() {
      const url = route("workspaces.store");

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
