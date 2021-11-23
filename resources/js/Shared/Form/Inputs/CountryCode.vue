<template>
  <select
    :class="className"
    class="text-center"
    :value="modelValue"
    @input="$emit('update:modelValue', $event.target.value)"
  >
    <option
      v-for="label, code in codes"
      :key="code"
      :value="code"
    >{{ label }}</option>
  </select>
</template>


<script>
export default {
  props: ["modelValue"],
  emits: ["update:modelValue"],

  components: {
    //
  },

  setup() {
    const className =
      "border p-2 rounded border-gray-300 block w-full text-lg text-center disabled:text-gray-500 disabled:bg-indigo-50 disabled:border-gray-200 transition-colors focus:outline-none focus:border-green-300 focus:ring-1 focus:ring-green-400";

    const codes = {
      51: 51,
      52: 52,
      1: 1,
    };

    return { className, codes };
  },
};
</script>
