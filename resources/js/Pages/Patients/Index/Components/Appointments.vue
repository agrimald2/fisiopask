<template>
  <div class="grid gap-4">
    <template
      v-for="appointment in appointments.data"
      :key="appointment.id"
    >
      <template v-if="past">
        <template v-if="isPast(appointment)">
          <item :model="appointment" :appointmentsLeft="appointments_left[appointment.id]"/>
        </template>
      </template>
      <template v-else>
        <template v-if="!isPast(appointment)">
          <item :model="appointment" :appointmentsLeft="appointments_left[appointment.id]"/>
        </template>
      </template>
    </template>
  </div>

  <div class="mt-6 flex justify-center">
    <pagination :links="appointments.links" />
  </div>
</template>


<script>
import Pagination from "@/Shared/Pagination.vue";
import Item from "./AppointmentItem";
import dates from "@/ui/dates.js";

export default {
  props: ["appointments", "past"],

  components: {
    Item,
    Pagination,
  },

  data() {
    return {
      count: 0,
    };
  },

  methods: {
    isPast($appointment) {
      return !dates.moment($appointment.date).isAfter();
    },
  },

  computed: {
    appointments_left() {
      let list = {};
      let countList = {};

      let reversed = this.appointments.data;
      reversed.reverse();

      reversed.map((x) => {
        countList[x.main_rate.id] = 0;
      });

      reversed.map((x) => {
        list[x.id] = x.appointments_paid - countList[x.main_rate.id];
        countList[x.main_rate.id] += 1;
      });

      reversed.reverse();

      return list;
    },
  },
};
</script>
