<template>
  <div class="border bg-white">
    <div
      class="cursor-pointer text-lg px-2 py-4"
      @click="is_expanded = !is_expanded"
    >
      <i class="fas fa-angle-down"></i>
      {{ title }}
    </div>
    <div
      v-show="is_expanded"
      class="border-t px-2 py-4"
    >
      <slot></slot>
    </div>
  </div>
</template>

<script>
export default {
  props: ["title"],
  data() {
    return {
      is_expanded: false,
    };
  },
};
</script>
