<template>
  <app-layout title="Historias Clínicas">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Crear una nueva historia clínica
      </h2>
    </template>

    <app-body>
      <div class="grid py-4 gap-5">
        <select
          class="border px-4 py-2"
          v-model="inputFormType"
        >
          <option
            v-for="formType, key in formTypes"
            :key="key"
            :value="key"
          >
            {{ formType }}
          </option>
        </select>

        <ui-button @click="submit(inputFormType)">Crear nueva historia clinica</ui-button>
      </div>
    </app-body>

  </app-layout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout.vue";
import AppBody from "@/Shared/Backend/AppBody";

import UiButton from "@/Shared/UI/Button";

export default {
  props: ["patientId", "formTypes"],

  components: {
    AppLayout,
    AppBody,

    UiButton,
  },

  data() {
    return {
      inputFormType: null,
    };
  },

  methods: {
    submit(formType) {
      const url = route("patients.histories.store", this.patientId);
      this.$inertia.post(url, { formType });
    },
  },
};
</script>
