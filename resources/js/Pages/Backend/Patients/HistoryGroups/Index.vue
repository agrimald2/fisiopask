<template>
  <app-layout title="Historias Clínicas">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Historias Clínicas
      </h2>
    </template>

    <div class="text-center pt-6">
      <ui-button @click="$inertia.visit(route('patients.historygroup.create', {'patientId' : patientId, 'doctorId' : doctor.id}))">Crear historia clínica</ui-button>
    </div>

    <app-body>
      <div class="py-4 px-1">
        <gridie
          class="text-left w-full"
          :rows="rows"
          :cols="cols"
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
  props: ["patientId", "rows", 'doctor'],

  components: {
    AppLayout,
    AppBody,

    UiButton,
    Gridie,
  },

  setup() {
    const cols = [
      c("patient.name", "Paciente"),
      c("created_at", "Creado").format((value) => dates.dateForLaravel(value)),
      {
        type: cells.Buttons,
        buttons: [
          {
            label: "Ver",
            clicked({ row }) {
              const url = route("patients.historygroup.show", row.id);
              Inertia.visit(url);
            },
          },
        ],
      },
    ];
    return { cols };
  },
};
</script>
