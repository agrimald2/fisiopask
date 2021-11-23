<template>
  <app-layout title="Doctores">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Doctores
      </h2>
    </template>

    <div class="mt-8 text-center">
      <jet-secondary-button @click="$inertia.visit(route('doctors.create'))">
        Añadir un doctor
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
      return this.model.map((x) => [
        x.user.name,
        x.user.email,
        x.birth_date,
        x.sex,
        x.phone,
        x.document_type,
        x.document_reference,
        x.id,
      ]);
    },
  },

  setup() {
    return {
      cols: [
        "Nombre",
        "Email",
        "Fecha de Nacimiento",
        "Sexo",
        "Teléfono",
        "Tipo Doc.",
        "# Documento",
        {
          name: "",
          element: ButtonCell,
          context: [
            {
              html: "<i class='fas fa-pencil-alt mr-2'></i>Editar",
              clicked(id) {
                Inertia.visit(route("doctors.edit", id));
              },
            },

            {
              html: "<i class='fas fa-clock mr-2'></i>Horario",
              clicked(id) {
                Inertia.visit(route("doctors.schedules.index", id));
              },
            },
          ],
        },
      ],
    };
  },
};
</script>
