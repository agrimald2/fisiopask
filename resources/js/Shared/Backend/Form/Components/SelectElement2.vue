<template>
  <select
    :value="modelValue"
    @input="$emit('update:modelValue', $event.target.value)"
    ref="input"
    class="
      border-gray-300
      focus:border-indigo-300
      focus:ring
      focus:ring-indigo-200
      focus:ring-opacity-50
      rounded-md
      shadow-sm
    "
  >
    <option v-for="(option, key) in options" :key="key" :value="options[key]">
      {{ option}} [{{key}}]
    </option>
  </select>
</template>


<script>
export default {
  props: ["modelValue", "options"],

  emits: ["update:modelValue"],

  methods: {
    focus() {
      this.$refs.input.focus();
    },
  },
};
</script>
