<template>
  <button
    class="text-center border rounded tracking-wide py-4 px-4"
    :class="className"
  >
    <slot></slot>
  </button>
</template>

<script>
import { ref, watch } from "@vue/runtime-core";
export default {
  setup(props, { attrs }) {
    let className = "bg-green-500 border-green-700 text-white";

    if (attrs.class) {
      className = attrs.class;
    }

    return { className };
  },
};
</script>
