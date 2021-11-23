<template>
  <app-layout title="Historias Clínicas">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Historias Clínicas
      </h2>
    </template>

    <app-body>
      <ui-form
        :model="model"
        @submitted="submit"
      />

      <div
        class="mt-8"
        v-show="id"
      >
        <ui-confirmation title="Eliminar historia clínica">
          <ui-button @click="destroy">Eliminar historia clínica definitivamente.</ui-button>
        </ui-confirmation>
      </div>
    </app-body>

  </app-layout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout.vue";
import AppBody from "@/Shared/Backend/AppBody";

import UiConfirmation from "@/Shared/Backend/CollapseConfirmation";
import UiButton from "@/Shared/UI/Button";
import UiForm from "./Components/Form";

export default {
  props: ["id", "model"],

  components: {
    AppLayout,
    AppBody,

    UiForm,
    UiButton,
    UiConfirmation,
  },

  methods: {
    submit(data) {
      const url = route("histories.update", this.id);
      this.$inertia.put(url, { data });
    },

    destroy() {
      if (confirm("Seguro?")) {
        const url = route("histories.destroy", this.id);
        this.$inertia.delete(url);
      }
    },
  },
};
</script>
