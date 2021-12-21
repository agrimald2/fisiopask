<template>
  <app-layout title="Historias Clínicas">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Historias Clínicas
      </h2>
    </template>

    <div class="text-center pt-6">
      <ui-button @click="$inertia.visit(route('medicalrevision.create', id))">Crear una revisión</ui-button>
    </div>

    <app-body>
      <div class="py-4 px-1">
        <gridie
          class="text-left w-full"
          :rows="medicalHistory"
          :cols="cols1"
        />
      </div>
    </app-body>

    <app-body>
      <div class="py-4 px-1">
        <gridie
          class="text-left w-full"
          :rows="revisions"
          :cols="cols2"
        />
      </div>
    </app-body>

  </app-layout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout.vue";
import AppBody from "@/Shared/Backend/AppBody";
import { Inertia } from "@inertiajs/inertia";

import { c, cells, Gridie } from "@ferchoposting/gridie";

import dates from "@/ui/dates.js";
import UiButton from "@/Shared/UI/Button";

export default {
  props: ["id", "medicalHistory", "revisions"],

  components: {
    AppLayout,
    AppBody,

    UiButton,
    Gridie,
  },

  setup() {
    const cols1 = [
      c("doctor.name", "Doctor"),
      c("description", "Descripción"),
      c("created_at", "Fecha").format((value) => dates.dateForLaravel(value)),
      {
        type: cells.Buttons,
        buttons: [
          {
            label: "Ver",
            clicked({ row }) {
              const url = route("histories.edit", row.id);
              Inertia.visit(url);
            },
          },
        ],
      },
    ];

    const cols2 = [
      c("doctor.name", "Doctor"),
      c("description", "Descripción"),
      c("created_at", "Fecha").format((value) => dates.dateForLaravel(value)),
      {
        type: cells.Buttons,
        buttons: [
          {
            label: "Ver",
            clicked({ row }) {
              const url = route("histories.edit", row.id);
              Inertia.visit(url);
            },
          },
        ],
      },
    ];
    return { cols1, cols2 };
  },
};
</script>
