<template>
  <div>
    <div class="text-center text-2xl">
      Especialidades de {{ model.user.name }}
    </div>
    <!-- Add specialty form -->
    <div class="mt-2 max-w-sm mx-auto">
      <div class="grid">
        <ui-select
          :options="specialtiesOptions"
          v-model="input.specialty_id"
        />
        <div class="mt-2 text-center">
          <div
            class="underline text-gray-700 inline-block cursor-pointer"
            @click="$inertia.visit(route('doctorSpecialties.index'))"
          >
            Crear una nueva especialidad
          </div>
        </div>
        <front-button
          class="mt-2"
          color="blue"
          @click="addSpecialty(input.specialty_id)"
        >Añadir especialidad</front-button>
      </div>
    </div>

    <!-- Show specialties -->
    <div class="mt-8 flex flex-wrap justify-center gap-4">
      <div
        class=" text-center"
        v-for="specialty in model.specialties"
        :key="specialty.id"
      >
        <div class="border bg-white rounded px-4 py-2 inline-block">
          <div class="flex items-center gap-4">
            {{ specialty.name }}
            <div
              class="text-red-500 underline cursor-pointer"
              @click="removeSpecialty(specialty.id)"
            >
              Quitar especialidad
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>


<script>
import UiSelect from "@/Shared/Backend/Form/Components/SelectElement";
import FrontButton from "@/Shared/Frontend/Button";

export default {
  props: ["model", "specialties"],

  components: {
    UiSelect,

    FrontButton,
  },

  computed: {
    specialtiesOptions() {
      const options = {};
      this.specialties.map((x) => {
        options[x.id] = x.name;
      });
      return options;
    },
  },

  data() {
    return {
      input: {
        specialty_id: null,
      },
    };
  },

  methods: {
    addSpecialty(specialty_id) {
      const doctor_id = this.model.id;
      const url = route("doctors.specialties.add");
      this.$inertia.post(
        url,
        {
          specialty_id,
          doctor_id,
        },
        { preserveScroll: true }
      );
    },

    removeSpecialty(specialty_id) {
      if (!confirm("Estas seguro?")) return;

      const doctor_id = this.model.id;
      const url = route("doctors.specialties.remove");
      this.$inertia.post(
        url,
        {
          specialty_id,
          doctor_id,
        },
        { preserveScroll: true }
      );
    },
  },
};
</script>
