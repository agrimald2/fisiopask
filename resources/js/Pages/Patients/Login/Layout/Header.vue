<template>
  <div class="pt-5 text-center">
    <div class="inline-block mx-auto">
      <img
        src="/assets/logo.png"
        width="200"
        class="cursor-pointer"
        @click="onClickLogo"
      >
    </div>
  </div>
</template>


<script>
export default {
  props: ["url"],

  methods: {
    onClickLogo() {
      let url = this.url;

      if (!this.url) {
        url = route("bookAppointment.index");
      }

      this.$inertia.visit(url);
    },
  },
};
</script>
