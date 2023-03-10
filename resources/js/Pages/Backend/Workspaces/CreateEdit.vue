<template>
  <app-layout title="Cubículos">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        <div class="flex items-center gap-4">
          <span
            class="underline cursor-pointer"
            @click="$inertia.visit(route('workspaces.index'))"
          >Cubículos</span>
          <i class="fas fa-angle-right"></i>
          {{ model ? 'Editar' : 'Crear' }} un Cubículo
        </div>
      </h2>
    </template>

    <div>
      <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">

        <!-- Default form -->
        <DefaultForm
          :model="model"
          :offices="officeIdOptions"
          class="mt-10 sm:mt-0"
        />

        <JetSectionBorder />

        <div
          v-if="model"
          class="text-center px-4 mt-6"
        >
          <FrontButton
            color="red"
            @click="deleteWorkspace"
          >
            Eliminar este Cubículo
          </FrontButton>
        </div>

      </div>
    </div>
  </app-layout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout.vue";
import JetSectionBorder from "@/Jetstream/SectionBorder.vue";

import FrontButton from "@/Shared/Frontend/Button";

import DefaultForm from "./Components/DefaultForm";

export default {
    props: ['model', 'offices'],

    components: {
      AppLayout,

      JetSectionBorder,
      FrontButton,

      DefaultForm,
    },

    computed: {
      officeIdOptions() {
        let list = {};
        this.offices.map((x) => {
          list[x.id] = x.name;
        });
        return list;
      }
    },

    methods: {
      deleteWorkspace() {
        if (!confirm("Estas seguro?")) return;

        const url = route("workspaces.destroy", this.model.id);
        this.$inertia.delete(url);
      },
    },
}
</script>
