<template>
  <div class="h-full">

    <div class="pt-4 text-center">
      <div class="bg-white inline-block mx-auto rounded px-4 py-2">
        <img
          src="/assets/logo.png"
          width="200"
          class="cursor-pointer"
          @click="$inertia.visit(route('frontend.appointments.index'))"
        >
      </div>
    </div>

    <div>
      <transition
        name="slide-fade"
        appear
      >
        <slot></slot>
      </transition>
    </div>

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
export default {
  props: [],

  components: {
    //
  },
};
</script>
