<template>
  <select
    class="border p-2 rounded border-gray-300 block w-full text-lg text-center
        disabled:text-gray-300 disabled:bg-gray-100 disabled:border-gray-200"
    :value="modelValue"
    @input="$emit('update:modelValue', $event.target.value)"
  >
    <option
      v-for="option, key in options"
      :key="key"
      :value="key"
      v-text="option"
    ></option>
    <slot></slot>
  </select>
</template>

<script>
export default {
  props: ["modelValue", "options"],
  emits: ["update:modelValue"],
};
</script>
