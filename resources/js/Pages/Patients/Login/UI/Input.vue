<template>
  <input
    class="border p-2 rounded border-gray-300 block w-full text-lg text-center
        disabled:text-gray-500 disabled:bg-gray-50 disabled:border-gray-300"
    :value="modelValue"
    @input="$emit('update:modelValue', $event.target.value)"
  />
</template>

<script>
export default {
  props: ["modelValue"],
  emits: ["update:modelValue"],
};
</script>
