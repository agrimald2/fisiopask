<template>
  <div
    v-if="message"
    class="text-center pt-2 border-t-4 border-red-400 text-red-500 text-xl"
  >{{ message }}</div>
</template>

<script>
export default {
  props: ["message"],
};
</script>
