<template>
  <div
    class="fixed z-20 inset-0"
    v-show="visible"
  >
    <div
      @click="hide"
      class="bg-black inset-0 opacity-50 absolute"
    ></div>
    <div
      @click.self="hide"
      class="absolute w-full"
    >
      <slot />
    </div>
  </div>
</template>


<script>
export default {
  props: [],
  emits: ["closed", "opened"],

  components: {
    //
  },

  data() {
    return {
      visible: false,
    };
  },

  methods: {
    show() {
      this.visible = true;
      this.$emit("opened");
    },
    hide() {
      this.visible = false;
      this.$emit("closed");
    },
  },
};
</script>
