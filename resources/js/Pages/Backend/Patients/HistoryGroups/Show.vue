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

    <!--form :action="route('patientFiles.upload', this.id)" method="post" enctype="multipart/form-data">
      <input type="file" name="file">
      <input type="hidden" name="_token" :value="csrf">
      <ui-button type="submit" name="submit">Subir un archivo</ui-button>
    </form-->

    <div v-for="file in files" :key="file.id">
      <a href="{{ asset(file.filename) }}" download>{{ file.filename }}</a>
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

    <app-body>
      <div class="py-4 px-1">
        <gridie
          class="text-left w-full"
          :rows="tests"
          :cols="cols3"
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
  props: ["id", "medicalHistory", "revisions", "files", "tests"],

  components: {
    AppLayout,
    AppBody,

    UiButton,
    Gridie,
  },

  data() {
    return {
      csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
    };
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
              const url = route("medicalhistory.show", row.id);
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
              const url = route("medicalrevision.show", row.id);
              Inertia.visit(url);
            },
          },
        ],
      },
    ];

    const cols3 = [
      c("test_type.name", "Tipo de Test"),
      c("doctor.fullname", "Doctor"),
      c("", "Compañía")
        .extend({
          html:true,
        })
        .format(function (row){
          if (row.company.name) return `<span> ${row.company.name} </span>`;
          else return `<span> NO APLICA </span>`;;
        }),
      c("", "Resultado")
      .extend({
        html: true,
      })
      .format(function (row) {
        let output = `<span>`;
        row.results.forEach(element => {
          output += element.data + " / ";
        });

        output = output.slice(0, output.length - 2);

        output += `</span>`;

        return output;
      }),
      c("created_at", "Fecha").format((value) => dates.dateForLaravel(value)),
    ];
    return { cols1, cols2, cols3 };
  },
};
</script>
