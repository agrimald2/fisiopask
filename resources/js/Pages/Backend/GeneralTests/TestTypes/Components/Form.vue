<template>
  <div>
    <jet-form-section @submitted="onSubmitted">
      <template #title>
        {{ model ? "Editar " : "Crear un nuevo" }} tipo de Test
      </template>

      <template #form>
        <!-- name -->
        <form-input
          label="Nombre"
          name="name"
          v-model="form.name"
          :form="form"
        />

        <!-- description -->
        <form-input
          label="Descripción"
          name="description"
          v-model="form.description"
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

    <!-- Delete button -->
    <div class="py-6">
      <jet-danger-button
        v-if="model"
        @click="destroy"
      >
        Eliminar Tipo de Test
      </jet-danger-button>
    </div>

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

        name: null,
        description: null,

        ...this.model,
      }),
    };
  },

  methods: {
    onSubmitted() {
      const model = this.model;
      let url = route("testTypes.store");

      if (model) {
        url = route("testTypes.update", model.id);
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
