<template>
  <button
    class="inline-block rounded px-4 py-4
        tracking-wider
        cursor-pointer hover:-translate-y-1 transform transition-transform
        text-center
        shadow-sm
        border
        active:translate-y-0.5"
    :class="{[style]: true, 'opacity-60': disabled}"
    :type="type"
    :disabled="disabled"
  >
    <slot></slot>
  </button>
</template>


<script>
export default {
  props: {
    color: null,
    type: {
      default: "button",
    },
    disabled: {
      default: false,
    },
  },

  components: {
    //
  },

  computed: {
    style() {
      const c = this.color;

      if (c == "red") {
        return "text-white bg-red-500 border-red-700";
      } else if (c == "pink") {
        return "text-white bg-pink-500 border-pink-700";
      } else if (c == "indigo") {
        return "text-white bg-indigo-500 border-indigo-700";
      } else if (c == "purple") {
        return "text-white bg-purple-500 border-purple-700";
      } else if (c == "yellow") {
        return "text-gray-900 bg-yellow-400 border-yellow-500";
      } else if (c == "blue") {
        return "text-white bg-blue-500 border-blue-700";
      } else if (c == "green") {
        return "text-white bg-green-500 border-green-700";
      } else {
        return "text-gray-800 bg-white border-gray-200";
      }
    },
  },
};
</script>
