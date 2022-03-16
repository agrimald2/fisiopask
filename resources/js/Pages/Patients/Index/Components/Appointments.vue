<template>
  <div class="grid gap-4">
    <template
      v-for="appointment in appointments"
      :key="appointment.id"
    >
      <template v-if="past">
        <template v-if="isPast(appointment)">
          <item :model="appointment"/>
        </template>
      </template>
      <template v-else>
        <template v-if="!isPast(appointment)">
          <item :model="appointment"/>
        </template>
      </template>
    </template>
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
    
  },
};
</script>
