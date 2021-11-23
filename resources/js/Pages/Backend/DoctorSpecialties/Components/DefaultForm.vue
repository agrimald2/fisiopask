<template>
  <div>
    <jet-form-section @submitted="onSubmitted">
      <template #title>
        Especialidad de Doctor
      </template>

      <template #description>
        Cada doctor puede tener múltiples especialidades.
      </template>

      <template #form>
        <!-- name -->
        <form-input
          label="Nombre"
          name="name"
          v-model="form.name"
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

    <div
      class="mt-4 text-center"
      v-if="model"
    >
      <front-button
        color="red"
        @click="destroy(model.id)"
      >Eliminar especialidad</front-button>
    </div>
  </div>
</template>

<script>
import JetButton from "@/Jetstream/Button.vue";
import JetFormSection from "@/Jetstream/FormSection.vue";
import JetActionMessage from "@/Jetstream/ActionMessage.vue";
import JetSecondaryButton from "@/Jetstream/SecondaryButton.vue";

import FrontButton from "@/Shared/Frontend/Button";
import FormInput from "@/Shared/Backend/Form/Input";

export default {
  props: ["model"],

  components: {
    JetActionMessage,
    JetButton,
    JetFormSection,
    JetSecondaryButton,

    FormInput,
    FrontButton,
  },

  data() {
    return {
      form: this.$inertia.form({
        _method: "POST",

        name: null,

        ...this.model,
      }),
    };
  },

  methods: {
    onSubmitted() {
      let url = route("doctorSpecialties.store");

      if (this.model) {
        url = route("doctorSpecialties.update", this.model.id);
        this.form._method = "PUT";
      }

      this.form.post(url, {
        // errorBag: "",
        preserveScroll: true,
        onSuccess: this.onSuccess,
      });
    },

    onSuccess() {
      // Clear inputs
    },

    destroy(id) {
      if (!confirm("Estas seguro?")) return;
      const url = route("doctorSpecialties.destroy", id);
      this.$inertia.delete(url, { preserveScroll: true });
    },
  },
};
</script>
