<template>
  <div class="grid gap-4">
    <template
      v-for="appointment in appointments"
      :key="appointment.id"
    >
      <item :model="appointment"/>
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
};
</script>
