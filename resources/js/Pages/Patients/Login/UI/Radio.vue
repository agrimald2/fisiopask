<template>
  <div class="flex flex-wrap gap-3">
    <div
      class="flex gap-4 items-center px-3 py-3 cursor-pointer
        hover:bg-green-50 border rounded"
      :class="{'border-transparent': !isPicked(key), 'text-green-700 border-green-300': isPicked(key)}"
      v-for="value, key in options"
      :key="key"
      @click="onClick(key)"
    >
      <div class="w-6 h-6 rounded-full border-2 border-green-400 relative">
        <div
          class="p-px absolute inset-1 rounded-full bg-green-400"
          :class="{'hidden': !isPicked(key)}"
        ></div>
      </div>
      <div>
        <div class="text-lg select-none">{{ value }}</div>
        <div class="text-gray-500 hidden"></div>
      </div>
    </div>
  </div>
</template>


<script>
export default {
  props: ["modelValue", "options"],
  emits: ["update:modelValue"],

  methods: {
    isPicked(key) {
      return this.modelValue == key;
    },

    onClick(key) {
      if (this.modelValue == key) {
        key = null;
      }
      this.$emit("update:modelValue", key);
    },
  },
};
</script>
