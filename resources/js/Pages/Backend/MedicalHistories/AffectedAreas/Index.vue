<template>
  <app-layout title="Áreas Afectadas">
    <template>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Áreas
      </h2>
    </template>

    <div class="mt-8 text-center">
      <JetSecondaryButton @click="$inertia.visit(route('affectedarea.create'))">
        Añadir una nueva Área
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
            return this.model.map((x) => [x.category, x.sub_category, x.id]);
        },
    },

    setup() {
      return {
        cols: [
          "Categoría",
          "Sub Categoría",
          {
            name: "",
            element: ButtonCell,
            context: [
              {
                html: "<i class='fas fa-edit mr-2'></i>Mostrar",
                clicked(id) {
                  Inertia.visit(route('affectedarea.edit', id));
                },
              },
            ],
          },
        ],
      };
    },
}
</script>