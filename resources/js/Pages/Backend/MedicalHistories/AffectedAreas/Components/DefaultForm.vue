<template>
  <JetFormSection @submitted="onSubmitted">
    <template #title>
      {{ model ? 'Editar' : 'Crear' }} un Área Afectada
    </template>

    <template #form>
      <!-- Category -->
      <FormInput
        label="Zona"
        name="category"
        v-model="form.category"
        :form="form"
      />

      <!-- Sub Category -->
      <FormInput
        label="Área"
        name="sub_category"
        v-model="form.sub_category"
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

        category: null,
        sub_category: null,

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
      const url = route("affectedarea.update", this.model.id);

      this.form._method = "PUT";

      this.form.post(url, {
        // errorBag: "",
        preserveScroll: true,
        onSuccess: this.onSuccess,
      });
    },

    submitCreateForm() {
      const url = route("affectedarea.store");

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
