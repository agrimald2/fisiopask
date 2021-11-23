<template>
  <div
    class=""
    v-show="Object.keys(doctorOptions).length > 0"
  >
    <div class="uppercase font-bold tracking-wide">
      Filtrar por Doctor
    </div>
    <ui-select
      class="mt-1"
      v-model="filters.doctorId"
      :options="doctorOptions"
    >
      <option value="">Todos</option>
    </ui-select>
    <div
      v-show="filters.doctorId"
      class="mt-2 flex justify-center"
      @click="filters.doctorId = null"
    >
      <ui-button>Mostrar todos los doctores</ui-button>
    </div>
  </div>

  <div
    class="mt-6"
    v-show="Object.keys(specialtyOptions).length > 0"
  >
    <div class="uppercase font-bold tracking-wide">
      Filtrar por Especialidad
    </div>
    <ui-select
      class="mt-1"
      v-model="filters.specialtyId"
      :options="specialtyOptions"
    >
      <option value="">Todas</option>
    </ui-select>
  </div>
</template>

<script>
import UiSelect from "../UI/Select.vue";
import UiButton from "../UI/Button.vue";

export default {
  props: ["modelValue", "specialtyOptions", "doctorOptions"],
  emits: ["update:modelValue"],

  components: {
    UiSelect,
    UiButton,
  },

  mounted() {
    this.filters = {
      ...this.filters,
      ...this.modelValue,
    };

    this.ensureDoctorIdExists();
  },

  data() {
    return {
      filters: {
        doctorId: null,
        specialtyId: null,
        officeId: null,
      },
    };
  },

  watch: {
    filters: {
      deep: true,
      handler(value) {
        this.$emit("update:modelValue", value);
      },
    },
  },

  methods: {
    ensureDoctorIdExists() {
      if (
        !Object.keys(this.doctorOptions).some((x) => x == this.filters.doctorId)
      ) {
        this.filters.doctorId = null;
      }
    },
  },
};
</script>
