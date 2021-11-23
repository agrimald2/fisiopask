<template>
  <div
    class="ticket"
    v-html="text"
  ></div>
</template>

<style>
.ticket {
  padding: 10px;
  font-family: monospace;
}
.ticket h1 {
  margin-top: 10px;
  font-size: 1.1rem;
}
</style>


<script>
export default {
  props: {
    text: null,
    autoPrint: {
      type: Boolean,
      default: true,
    },
  },

  mounted() {
    if (this.autoPrint) {
      print();
    }
  },

  data() {
    return {
      //
    };
  },
};
</script>
