<template>
  <div
    class="inline-block pl-2 pr-4 py-2
    cursor-pointer group border rounded-xl border-gray-100"
    @click="toggle"
  >
    <div class="flex items-center gap-4">
      <div class="group-hover:scale-95 transform transition-transform">
        <div
          class="w-8 h-8 rounded-full flex items-center justify-center transition border"
          :class="{'bg-blue-700 border-transparent': active}"
        >
          <i
            v-show="active"
            class="fas fa-check text-white"
          ></i>
        </div>
      </div>
      <div class="select-none">{{ label }}</div>
    </div>
  </div>
</template>


<script>
export default {
  props: ["modelValue", "label"],

  emits: ["update:modelValue"],

  components: {
    //
  },

  methods: {
    toggle() {
      this.$emit("update:modelValue", !this.modelValue);
    },
  },

  computed: {
    active() {
      return this.modelValue;
    },
  },
};
</script>
