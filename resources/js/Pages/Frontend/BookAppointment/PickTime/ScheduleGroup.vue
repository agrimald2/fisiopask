<template>
  <div
    class="border rounded"
    :class="{'border-green-300': expanded}"
  >

    <!-- Header -->
    <div
      class="py-4 text-xl flex items-center gap-5
        cursor-pointer"
      :class="{ 'text-white bg-green-400': expanded, 'text-gray-400 hover:bg-gray-100': !expanded }"
      @click="expanded = !expanded"
    >
      <div class="text-center pl-4">
        {{ dates.hourForHumans(startTime) }}
      </div>
      <div
        class="flex-grow border-t"
        :class="{'border-white': expanded}"
      ></div>
      <div class="text-sm uppercase font-bold pr-2">
        {{ schedules.length }} disponibles
      </div>
    </div>
    <!-- Options -->
    <div
      class="grid border-t"
      :class="{ 'hidden': !expanded, 'border-green-400': expanded }"
    >
      <schedule-item
        v-for="schedule in schedules"
        :key="schedule.id"
        :schedule="schedule"
        @picked="onPicked"
      />
    </div>
  </div>
</template>


<script>
import dates from "@/ui/dates.js";
import ScheduleItem from "./ScheduleItem.vue";

export default {
  props: ["startTime", "schedules", "onPicked"],

  components: {
    ScheduleItem,
  },

  setup() {
    return { dates };
  },

  data() {
    return {
      expanded: false,
    };
  },

  watch: {
    schedules() {
      this.expanded = this.schedules.length <= 2;
    },
  },
};
</script>
