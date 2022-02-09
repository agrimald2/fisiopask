<template>
  <div>
    <jet-form-section @submitted="onSubmitted">
      <template #title>
        Crear un nuevo Paciente
      </template>

      <template #form>
        <form-input
          label="Nombre"
          name="name"
          v-model="form.name"
          type="text"
          :form="form"
        />

        <form-input
          label="Apellido Paterno"
          name="lastname1"
          v-model="form.lastname1"
          type="text"
          :form="form"
        />

        <form-input
          label="Apellido Materno"
          name="lastname2"
          v-model="form.lastname2"
          type="text"
          :form="form"
        />

        <form-input
          label="Email"
          name="email"
          v-model="form.email"
          type="text"
          :form="form"
        />

        <form-input
            label="DNI"
            name="dni"
            v-model="form.dni"
            type="text"
            :form="form"
        />

        <form-input
          label="Fecha de Nacimiento"
          name="birth_date"
          v-model="form.birth_date"
          type="date"
          :form="form"
        />

        <form-input
          label="Sexo"
          name="sex"
          v-model="form.sex"
          type="select"
          :options="sexOptions"
          :form="form"
        />

        <form-input
            label="Teléfono"
            name="phone"
            v-model="form.phone"
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
  props: ['dni'],

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
      previousTestType: null,
      form: this.$inertia.form({
        _method: "POST",

        name: null,
        lastname1: null,
        lastname2: null,
        email: null,
        dni: this.dni,
        birth_date: null,
        sex: null,
        phone: null,
      }),
    };
  },

  computed: {
      sexOptions() {
          let list = {};

          list[0] = "M";
          list[1] = "F";
          list[2] = "NB";
          return list;
      },
  },
  
  methods: {
    onSubmitted() {
      const model = this.model;
      let url = route("tests.createPatient");

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