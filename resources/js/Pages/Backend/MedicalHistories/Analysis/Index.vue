<template>
  <app-layout title="Análisis">
    <template>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Análisis
      </h2>
    </template>

    <div class="mt-8 text-center">
      <JetSecondaryButton @click="$inertia.visit(route('analysis.create'))">
        Añadir un nuevo Análisis
      </JetSecondaryButton>
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

import Grid from "@/Shared/Grid/Grid";
import ButtonCell from "@/Shared/Grid/Cells/ButtonCell";

import JetSecondaryButton from "@/Jetstream/SecondaryButton";

export default {
    props: ['model'],

    components: {
        AppLayout,

        JetSecondaryButton,

        Grid,
    },

    computed: {
        rows() {
            return this.model.map((x) => [x.name, x.description, x.id]);
        },
    },

    setup() {
      return {
        cols: [
          "Nombre",
          "Descripción",
          {
            name: "",
            element: ButtonCell,
            context: [
              {
                html: "<i class='fas fa-edit mr-2'></i>Mostrar",
                clicked(id) {
                  Inertia.visit(route('analysis.edit', id));
                },
              },
            ],
          },
        ],
      };
    },
}
</script>