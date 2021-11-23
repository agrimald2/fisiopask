<template>
  <app-layout title="Especialidades">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Especialidades de Doctor
      </h2>
    </template>

    <div class="mt-8 text-center">
      <jet-secondary-button @click="$inertia.visit(route('doctorSpecialties.create'))">
        Añadir una especialidad
      </jet-secondary-button>
    </div>

    <div class="mt-12 sm:px-2 md:px-3 lg:px-4 overflow-x-auto">
      <grid
        :cols="cols"
        :rows="rows"
      />
    </div>

  </app-layout>
</template>

<script>
import { Inertia } from "@inertiajs/inertia";

import AppLayout from "@/Layouts/AppLayout.vue";
import AppBody from "@/Shared/Backend/AppBody";
import Grid from "@/Shared/Grid/Grid";
import ButtonCell from "@/Shared/Grid/Cells/ButtonCell";

import JetSecondaryButton from "@/Jetstream/SecondaryButton";

export default {
  props: ["model"],

  components: {
    AppLayout,
    AppBody,

    Grid,

    JetSecondaryButton,
  },

  computed: {
    rows() {
      return this.model.map((x) => [x.name, x.doctors, x.id]);
    },
  },

  setup() {
    return {
      cols: [
        "Nombre",
        {
          name: "Doctores",
          format(model) {
            return model.length;
          },
        },
        {
          name: "",
          element: ButtonCell,
          context: [
            {
              html: "<i class='fas fa-pencil-alt mr-2'></i>Editar",
              clicked(id) {
                Inertia.visit(route("doctorSpecialties.edit", id));
              },
            },
          ],
        },
      ],
    };
  },
};
</script>
