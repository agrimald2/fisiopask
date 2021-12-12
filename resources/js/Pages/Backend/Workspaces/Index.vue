<template>
  <app-layout title="Cubículos">
    <template>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Cubículos
      </h2>
    </template>

    <div class="mt-8 text-center">
      <jet-secondary-button @click="$inertia.visit(route('workspaces.create'))">
        Añadir un cubículo
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

import Grid from "@/Shared/Grid/Grid";
import ButtonCell from "@/Shared/Grid/Cells/ButtonCell";

import JetSecondaryButton from "@/Jetstream/SecondaryButton";

export default {
    props: ['model'],

    components: {
        AppLayout,

        Grid,

        JetSecondaryButton,
    },

    computed: {
        rows() {
            return this.model.map((x) => [x.name, x.description, x.office.name, x.id]);
        },
    },

    setup() {
        return {
            cols: [
                "Nombre",
                "Descripción",
                "Sucursal",
                {
                    name: "",
                    element: ButtonCell,
                    context: [
                        {
                            html: "<i class='fas fa-pencil-alt mr-2'></i>Editar",
                            clicked(id) {
                                Inertia.visit(route("workspaces.edit", id));
                            },
                        },
                    ],
                },
            ],
        };
    },
}
</script>