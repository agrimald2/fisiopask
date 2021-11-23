<template>
  <div></div>
</template>


<script>
import notify from "@/ui/notify";

export default {
  props: [],

  components: {
    //
  },

  mounted() {
    const flash = this.$page.props.flash;

    if (!flash) return;

    let time = 0;

    flash.forEach((x) => {
      if (x) {
        const type = x[0];
        const message = x[1];

        if (notify.hasOwnProperty(type)) {
          setTimeout(() => notify[type](message), time);
          time += 200;
        }
      }
    });
  },

  data() {
    return {
      //
    };
  },
};
</script>
