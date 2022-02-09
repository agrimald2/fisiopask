<template>
  <Link :href="'/'">
  <img style="max-width:5vw"
    src="/assets/logo.png"
    alt=""
  >
  </Link>
</template>

<script>
import { Link } from "@inertiajs/inertia-vue3";

export default {
  components: {
    Link,
  },
};
</script>
