<template>
  <div class="h-full">

    <layout-header :url="url" />

    <transition
      name="slide-fade"
      appear
    >
      <slot></slot>
    </transition>

    <div class="py-12"></div>

  </div>
</template>


<style>
html,
body,
#app {
  height: 100%;
}
/**
 * Transition
 */

.slide-fade-enter-active,
.slide-fade-leave-active {
  transition: all 0.3s ease-out;
}

.slide-fade-enter-from,
.slide-fade-leave-to {
  transform: translateX(40vw);
  opacity: 0;
}
</style>

<script>
import LayoutHeader from "./Header.vue";

export default {
  props: ["url"],
  components: {
    LayoutHeader,
  },
};
</script>
