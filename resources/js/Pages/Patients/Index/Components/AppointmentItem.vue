<template>
  <div class="border rounded">
    <!-- Summary -->
    <div
      class="select-none p-2 cursor-pointer hover:bg-gray-50"
      @click="expanded = !expanded"
    >
      <div class="flex flex-wrap items-center justify-between">
        <div>
          <div class="text-gray-500 text-sm">
            {{ model.office }}
          </div>
          <div class="capitalize text-lg font-bold">
            {{ dates.dateForHumans(model.date) }}
          </div>
        </div>
        <div
          v-if="appointmentsLeft <= 0"
          class="px-2 py-1 rounded font-bold text-sm bg-red-500 text-white"
        >No Pagada</div>
        <div
          v-if="model.status==4"
          class="px-2 py-1 rounded font-bold text-sm bg-red-500 text-white"
        >Cancelada</div>
        <div
          v-else-if="model.status==3"
          class="px-2 py-1 rounded font-bold text-sm bg-green-500 text-white"
        >Asistida</div>
        <div
          v-else-if="model.status==2"
          class="px-2 py-1 rounded font-bold text-sm bg-red-500 text-white"
        >No asistida</div>
        <div
          v-else
          v-show="isPendient"
          class="px-2 py-1 rounded font-bold text-sm bg-yellow-400"
        >Pendiente</div>
      </div>
      <div class="flex gap-4 items-center">
        {{ model.start }}
        <i class="fas fa-arrow-right"></i>
        {{ model.end }}
      </div>
      <div class="mt-3 text-sm medium-text">
        {{ model.doctor.name }} {{ model.doctor.lastname }}
      </div>
    </div>

    <!-- Body -->
    <div
      class="border-t py-3 bg-gray-50 text-center"
      v-show="expanded && isPendient && model.status != 4"
    >
      <ui-button
        color="red"
        @click="cancel"
      >Cancelar cita</ui-button>
    </div>

  </div>
</template>

<script>
import UiButton from "@/Shared/Frontend/Button";
import dates from "@/ui/dates.js";

export default {
  props: ["model", "appointmentsLeft"],

  components: {
    UiButton,
  },

  data() {
    return { expanded: false, can_assist: true};
  },

  setup() {
    return { dates };
  },

  methods: {
    cancel() {
      const id = this.model.id;
      const url = route("area.patients.cancel", id);
      this.$inertia.visit(url);
    },
    pay() {
      const id = this.model.id;
      const url = route("area.patients.pay", id);
      this.$inertia.visit(url);
    }
  },

  computed: {
    isPendient() {
      return dates.moment(this.model.date).isAfter();
    },
  },
};
</script>
