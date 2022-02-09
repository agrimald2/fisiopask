<template>
  <div class="grid gap-4">
    <template
      v-for="test in tests"
      :key="test.id"
    >
      <item :model="test"/>
    </template>
  </div>
</template>

<script>
import Pagination from "@/Shared/Pagination.vue";
import Item from "./TestItem.vue";

export default {
  props: ["tests"],

  components: {
    Item,
    Pagination,
  },
};
</script>
