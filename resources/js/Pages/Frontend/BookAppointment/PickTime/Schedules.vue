<template>
  <div class="mt-8 grid gap-6">
    <!-- If records -->
    <template v-if="thereAreSchedulesAvailable()">
      <schedule-group
        v-for="scheduleGroup, key in groupedSchedules"
        :key="key"
        :startTime="scheduleGroup.start_time"
        :schedules="filter(scheduleGroup.schedules)"
        v-show="filter(scheduleGroup.schedules).length > 0"
        :onPicked="onPicked"
      />
    </template>

    <!-- If no records -->
    <div
      v-else
      class="px-2 text-center text-gray-500 p-3 bg-gray-100"
    >
      <div class="text-2xl">
        Sin Resultados!
      </div>
      <div class="text-lg mt-2">
        No hay ningún doctor disponible en ésta fecha.
      </div>
    </div>

  </div>
</template>


<script>
import dates from "@/ui/dates.js";
import ScheduleGroup from "./ScheduleGroup.vue";

export default {
  props: ["groupedSchedules", "filter"],
  emits: ["picked"],

  components: {
    ScheduleGroup,
  },

  setup() {
    return { dates };
  },

  data() {
    return {
      expanded: [],
    };
  },

  methods: {
    onPicked() {
      this.$emit("picked", ...arguments);
    },

    thereAreSchedulesAvailable() {
      const groupLength = this.groupedSchedules.length;

      const atLeastOneGroupHasLength =
        this.groupedSchedules.some((x) => this.filter(x.schedules).length) > 0;

      return groupLength && atLeastOneGroupHasLength;
    },
  },
};
</script>
