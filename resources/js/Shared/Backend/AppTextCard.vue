<template>
  <div class="p-6 sm:px-20 bg-white border-b border-gray-200">
    <div
      class="text-2xl"
      v-if="label"
    >
      {{ label }}
    </div>

    <div class="mt-6 text-gray-500">
      <slot />
    </div>
  </div>
</template>


<script>
export default {
  props: ["label"],

  components: {
    //
  },

  data() {
    return {
      //
    };
  },
};
</script>
