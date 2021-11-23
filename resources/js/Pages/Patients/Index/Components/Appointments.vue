<template>
  <div class="grid gap-4">
    <template
      v-for="appointment in appointments.data"
      :key="appointment.id"
    >
      <item :model="appointment" />
    </template>
  </div>

  <div class="mt-6 flex justify-center">
    <pagination :links="appointments.links" />
  </div>
</template>


<script>
import Pagination from "@/Shared/Pagination.vue";
import Item from "./AppointmentItem";

export default {
  props: ["appointments"],

  components: {
    Item,
    Pagination,
  },
};
</script>
