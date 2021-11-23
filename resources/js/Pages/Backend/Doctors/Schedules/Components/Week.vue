<template>
  <div>
    <modal ref="modal">
      <edit-schedule-form
        :schedule="selectedSchedule"
        :modal="$refs.modal"
      />
    </modal>

    <div class="grid sm:grid-cols-2 md:grid-cols-3 md:px-4 lg:grid-cols-4 3xl:grid-cols-7 gap-4">
      <div
        class="border bg-gray-200 rounded-lg pt-4 pb-6 px-4"
        v-for="day in 7"
        :key="day"
      >
        <div class="text-lg uppercase font-bold text-center bg-gray-800 text-white rounded-xl py-2">{{ days[day] }}</div>
        <div class="mt-4 grid gap-4">
          <div
            class=""
            v-for="schedule in filterByDay(schedules, day)"
            :key="schedule.id"
            @click="editSchedule(schedule)"
          >
            <div
              class="border rounded bg-white flex items-center gap-4 px-3
            cursor-pointer hover:scale-95 hover:translate-x transition-transform transform"
              :style="{'border-color': str2hex(schedule.office.name)}"
            >
              <div class="border-r border-gray-100 pr-3 py-3">
                <i class="fas fa-cog"></i>
              </div>
              <div class="flex-grow flex items-center gap-4 justify-around">
                {{ schedule.start_time }}
                <i class="fas fa-arrow-right"></i>
                {{ schedule.end_time }}
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>


<script>
import Modal from "@/Shared/Modal/Modal";
import EditScheduleForm from "./EditScheduleForm";
import str2hex from "@/ui/str2hex";

export default {
  props: ["schedules"],

  components: {
    Modal,
    EditScheduleForm,
  },

  data() {
    return {
      selectedSchedule: null,
    };
  },

  setup() {
    return {
      str2hex,
      days: {
        1: "Lunes",
        2: "Martes",
        3: "Miercoles",
        4: "Jueves",
        5: "Viernes",
        6: "Sabado",
        7: "Domingo",
      },
    };
  },

  methods: {
    filterByDay(schedules, day) {
      return schedules.filter((x) => x.week_day == day);
    },
    editSchedule(schedule) {
      this.selectedSchedule = schedule;
      this.$refs.modal.show();
    },
  },
};
</script>
