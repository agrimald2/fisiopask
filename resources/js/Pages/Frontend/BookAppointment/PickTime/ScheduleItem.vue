<template>
  <div
    class="border-t"
    :class="{'bg-green-50': expanded}"
  >
    <div
      class="grid py-4 px-2
            cursor-pointer"
      :class="{ 'hover:bg-gray-100': !expanded }"
      @click="expanded = !expanded"
    >
      <div class="text-gray-700 text-center">
        {{ schedule.doctor }}
      </div>
      <div
        class="flex items-center justify-center gap-4
      text-sm text-gray-500 pt-2"
        :class="{'': !expanded }"
      >
        <div class="">
          {{ dates.hourForHumans(schedule.start_time) }}
        </div>
        <div class="">
          <i class="fas fa-caret-right"></i>
        </div>
        <div class="">
          {{ dates.hourForHumans(schedule.end_time) }}
        </div>
      </div>
    </div>
    <div
      v-show="expanded"
      class="grid px-4 pb-4 gap-4"
    >
      <ui-button @click="pick()">
        <div class="flex items-center justify-between">
          <div class="text-lg text-center flex-grow">
            Generar Cita
          </div>
          <i class="text-3xl fas fa-angle-right"></i>
        </div>
      </ui-button>
    </div>
  </div>
</template>


<script>
import UiButton from "../UI/Button.vue";
import dates from "@/ui/dates";

export default {
  props: ["schedule"],
  emits: ["picked"],

  components: {
    UiButton,
  },

  setup() {
    return { dates };
  },

  data() {
    return {
      expanded: false,
    };
  },

  methods: {
    pick() {
      this.$emit("picked", this.schedule);
      this.expanded = false;
    },
  },
};
</script>
