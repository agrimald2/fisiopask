<template>
  <div class="mt-8 max-w-md mx-auto">
    <input-tel v-model="phone" />

    <div class="mt-8 text-2xl text-center">
      Phone: {{ phone }}
    </div>

  </div>
</template>

<script>
import InputTel from "@/Shared/Form/Inputs/Tel.vue";

export default {
  components: {
    InputTel,
  },

  data() {
    return {
      phone: null,
    };
  },
};
</script>
