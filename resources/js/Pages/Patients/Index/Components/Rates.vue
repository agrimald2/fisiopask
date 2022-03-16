<template>
  <div class="grid gap-4">
    <template
      v-for="rate in rates"
      :key="rate.id"
    >
      <template v-if="rate.state == 0">
        <item :model="rate"/>
      </template>
    </template>
  </div>
</template>

<script>
import Pagination from "@/Shared/Pagination.vue";
import Item from "./RateItem.vue";

export default {
  props: ["rates"],

  components: {
    Item,
    Pagination,
  },
};
</script>
