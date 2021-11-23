<template>
  <div class="flex justify-center gap-4">
    <div
      v-for="(button, key) in context"
      :key="key"
      class="py-2 px-2 rounded cursor-pointer hover:scale-105 hover:-translate-y-1 transform transition-transform shadow"
      :class="{[button.class]: button.class, 'bg-blue-500 text-white shadow': !button.class }"
      @click="button.clicked(value)"
      v-html="button.html"
    >
    </div>
  </div>
</template>

<script>
export default {
  props: ["value", "context"],
};
</script>
