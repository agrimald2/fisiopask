<template>
  <div>
    <div class="text-center text-2xl">
      Subfamilias de {{ model.user.name }}
    </div>
    <!-- Add subfamily form -->
    <div class="mt-2 max-w-sm mx-auto">
      <div class="grid">
        <ui-select
          :options="subfamiliesOptions"
          v-model="input.subfamily_id"
        />
        <div class="mt-2 text-center">
          <div
            class="underline text-gray-700 inline-block cursor-pointer"
            @click="$inertia.visit(route('subfamilies.index'))"
          >
            Crear una nueva subfamilia
          </div>
        </div>
        <front-button
          class="mt-2"
          color="blue"
          @click="addSubfamily(input.subfamily_id)"
        >Añadir subfamilia</front-button>
      </div>
    </div>

    <!-- Show subfamilies -->
    <div class="mt-8 flex flex-wrap justify-center gap-4">
      <div
        class=" text-center"
        v-for="subfamily in model.subfamilies"
        :key="subfamily.id"
      >
        <div class="border bg-white rounded px-4 py-2 inline-block">
          <div class="flex items-center gap-4">
            {{ subfamily.name_with_family }}
            <div
              class="text-red-500 underline cursor-pointer"
              @click="removeSubfamily(subfamily.id)"
            >
              Quitar subfamilia
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
  props: ["model", "subfamilies"],

  components: {
    UiSelect,

    FrontButton,
  },

  computed: {
    subfamiliesOptions() {
      const options = {};
      this.subfamilies.map((x) => {
        options[x.id] = x.name_with_family;
      });
      return options;
    },
  },

  data() {
    return {
      input: {
        subfamily_id: null,
      },
    };
  },

  methods: {
    addSubfamily(subfamily_id) {
      const doctor_id = this.model.id;
      const url = route("doctors.subfamilies.add");
      this.$inertia.post(
        url,
        {
          subfamily_id,
          doctor_id,
        },
        { preserveScroll: true }
      );
    },

    removeSubfamily(subfamily_id) {
      if (!confirm("Estas seguro?")) return;

      const doctor_id = this.model.id;
      const url = route("doctors.subfamilies.remove");
      this.$inertia.post(
        url,
        {
          subfamily_id,
          doctor_id,
        },
        { preserveScroll: true }
      );
    },
  },
};
</script>
