<template>
  <app-layout title="Especialidades">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        <div class="flex items-center gap-4">
          <span
            class="underline cursor-pointer"
            @click="$inertia.visit(route('doctorSpecialties.index'))"
          >Especialidades</span>
          <i class="fas fa-angle-right"></i>
          {{ model ? 'Editar' : 'Crear' }} una Especialidad
        </div>
      </h2>
    </template>

    <div>
      <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">

        <!-- Default form -->
        <default-form
          :model="model"
          class="mt-10 sm:mt-0"
        />

        <div v-if="model">
          <jet-section-border />

          <div class="text-center mt-8">
            <div class="text-2xl">Doctores:</div>
            <div
              class="mt-2 text-gray-500"
              v-if="model.doctors.length == 0"
            >
              Ningún doctor tiene esta especialidad!
              <div class="mt-4">
                <div
                  class="inline-block underline cursor-pointer"
                  @click="$inertia.visit(route('doctors.index'))"
                >Añadir doctores a esta especialidad</div>
              </div>
            </div>
            <div class="mt-2 grid gap-4">
              <div
                v-for="doctor in model.doctors"
                :key="doctor.id"
              >
                <div
                  class="border inline-block py-2 px-4 bg-white rounded cursor-pointer hover:text-blue-400"
                  @click="$inertia.visit(route('doctors.edit', doctor.id))"
                >
                  {{ doctor.user.name }}
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>
    </div>
  </app-layout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout.vue";
import JetSectionBorder from "@/Jetstream/SectionBorder.vue";

import DefaultForm from "./Components/DefaultForm";

export default {
  props: ["model"],

  components: {
    AppLayout,
    JetSectionBorder,

    DefaultForm,
  },

  data() {
    return {
      //
    };
  },

  methods: {},
};
</script>
