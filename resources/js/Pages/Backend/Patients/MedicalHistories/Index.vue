<template>
  <app-layout title="Historia Médica">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Historia Médica
      </h2>
    </template>

    {{ model.id }} <br>

    {{ model.patient.name }} <br>

    {{ model.background }} <br>
    {{ model.warnings }} <br>
    {{ model.description }} <br>

    {{ model.pain_scale }} <br>
    {{ model.force_scale }} <br>
    {{ model.joint_range }} <br>
    {{ model.recovery_progress }} <br>

    {{ model.diagnostic_id }} <br>
    {{ model.treatment_id }} <br>
    {{ model.analysis_id }} <br>
    {{ model.affected_area_id }} <br>

    {{ model.history_group_id }}
  </app-layout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout.vue";
import AppBody from "@/Shared/Backend/AppBody";
import Grid from "@/Shared/Grid/Grid";

export default {
  props: ["model"],

  components: {
    AppLayout,
    AppBody,

    Grid,
  },

  mounted() {
      console.log(this.model);
  },
};
</script>
