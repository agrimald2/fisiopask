<template>
  <app-layout title="Encuestas">
    <template>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Encuestas
      </h2>
    </template>

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

import Grid from "@/Shared/Grid/Grid";
import ButtonCell from "@/Shared/Grid/Cells/ButtonCell";

export default {
    props: ['model'],

    components: {
        AppLayout,

        Grid,
    },

    computed: {
        rows() {
            return this.model.map((x) => [x.survey_date, x.appointment.patient.name, x.appointment.doctor.name, x.id]);
        },
    },

    setup() {
      return {
        cols: [
          "Fecha",
          "Paciente",
          "Doctor",
          {
            name: "",
            element: ButtonCell,
            context: [
              {
                html: "<i class='fas fa-eye mr-2'></i>Mostrar",
                clicked(id) {
                  Inertia.visit(route('surveys.show', id));
                },
              },
            ],
          },
        ],
      };
    },
}
</script>