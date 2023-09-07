<template>
  <div>
    <jet-form-section @submitted="onSubmitted">
      <template #title> {{ model ? "Editar " : "Crear un nuevo" }} Doctor </template>

      <template #description>
        Un doctor puede acceder a la plataforma con su email y contraseña.
      </template>

      <template #form>
        <!-- name -->
        <form-input label="Nombre" name="name" v-model="form.name" :form="form" />

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

        <!-- birth_date -->
        <form-input
          label="Fecha de Nacimiento"
          name="birth_date"
          v-model="form.birth_date"
          type="date"
          :form="form"
        />

        <!-- sex -->
        <form-input
          label="Sexo"
          name="sex"
          v-model="form.sex"
          type="select"
          :options="$page.props.doctorsConfig.sex"
          :form="form"
        />

        <!-- phone -->
        <form-input
          label="Teléfono"
          name="phone"
          v-model="form.phone"
          type="text"
          :form="form"
        />

        <!-- document_type -->
        <form-input
          label="Tipo de Documento"
          name="document_type"
          v-model="form.document_type"
          type="select"
          :options="$page.props.doctorsConfig.document_type"
          :form="form"
        />

        <!-- document_reference -->
        <form-input
          label="Número de Documento"
          name="document_reference"
          v-model="form.document_reference"
          type="text"
          :form="form"
        />

        <!-- Cubiculo  -->
        <form-input
          label="Cubículo"
          name="workspace"
          v-model="form.workspace_id"
          type="select"
          :options="workspaces"
          :form="form"
        />
      </template>

      <template #actions>
        <jet-action-message :on="form.recentlySuccessful" class="mr-3">
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
      <jet-danger-button v-if="model" @click="destroy">
        Eliminar doctor
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
        birth_date: null,
        sex: null,
        phone: null,
        document_type: null,
        document_reference: null,
        workspace_id: null,

        ...this.model,
      }),
    };
  },

  methods: {
    onSubmitted() {
      const model = this.model;
      let url = route("doctors.store");

      if (model) {
        url = route("doctors.update", model.id);
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
        confirm("Esta acción no puede deshacerse, presione cancelar si no está seguro.")
      ) {
        this.$inertia.delete(route("doctors.destroy", this.form.id));
      }
    },
  },
};
</script>
