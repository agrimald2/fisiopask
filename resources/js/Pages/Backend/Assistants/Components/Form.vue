<template>
  <div>
    <jet-form-section @submitted="onSubmitted">
      <template #title>
        {{ model ? "Editar " : "Crear un nuevo" }} Asistente
      </template>

      <template #description>
        Un asistente puede acceder a la plataforma con su email y contraseña.
      </template>

      <template #form>
        <!-- name -->
        <form-input
          label="Nombre"
          name="name"
          v-model="form.name"
          :form="form"
        />

        <!-- lastname -->
        <form-input
          label="Apellido"
          name="lastname"
          v-model="form.lastname"
          :form="form"
        />

        <!-- email -->
        <form-input
          label="Email"
          name="user.email"
          v-model="form.user.email"
          :form="form"
        />

        <!-- password -->
        <form-input
          label="Password"
          name="user.password"
          v-model="form.user.password"
          type="password"
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
        Eliminar asistente
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
  props: ["model", "workspaces"],

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

        user: {
          email: null,
          password: null,
        },

        name: null,
        lastname: null,

        ...this.model,
      }),
    };
  },

  methods: {
    onSubmitted() {
      const model = this.model;
      let url = route("assistants.store");

      if (model) {
        url = route("assistants.update", model.id);
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
        this.$inertia.delete(route("assistants.destroy", this.form.id));
      }
    },
  },
};
</script>
