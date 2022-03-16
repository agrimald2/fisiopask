<template>
  <div
    class="max-w-sm mx-auto bg-white m-12 rounded-xl"
    v-if="schedule"
  >
    <div class="text-xl text-center font-bold border-b py-4">Editar Horario</div>

    <div class="mt-4 text-center text-lg">
      <div class="text-xs font-bold text-gray-400 uppercase tracking-wider">Sucursal</div>
      {{ schedule.office.name }}
    </div>

    <div class="mt-4">
      <div class="text-center text-xs font-bold text-gray-400 uppercase tracking-wider">Horario</div>
      <div class="flex justify-center gap-6 items-center">
        {{ schedule.start_time }}
        <i class="fas fa-arrow-right"></i>
        {{ schedule.end_time }}
      </div>
    </div>

    <div class="mt-4 py-4">
      <div class="text-center">
        <front-button
          @click="deleteSchedule"
          color="red"
          :disabled="loading"
        >Eliminar horario</front-button>
      </div>
    </div>

  </div>
</template>


<script>
import FrontButton from "@/Shared/Frontend/Button";

export default {
  props: ["schedule", "modal"],

  components: {
    FrontButton,
  },

  data() {
    return {
      loading: false,
    };
  },

  methods: {
    deleteSchedule() {
      //if (!confirm("Estas seguro?")) return;
      this.loading = true;
      this.$inertia.delete(route("schedules.destroy", this.schedule.id), {
        preserveScroll: true,
        onSuccess: this.done,
        onError: this.done,
      });
      this.modal.hide();
    },

    done() {
      this.loading = false;
    },
  },
};
</script>
