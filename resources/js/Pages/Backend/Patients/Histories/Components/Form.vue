<template>
  <form @submit.prevent="submit">
    <div class="px-2 py-2 grid gap-4">

      <template
        v-for="field, key in fields"
        :key="key"
      >
        <div class="">
          <div class="font-bold capitalize">{{field.label}}</div>
          <input
            v-model="values[field.key]"
            type="text"
            class="w-full border px-4 py-2 rounded"
          >
        </div>
      </template>

      <ui-button @click="submit">Guardar</ui-button>
    </div>
  </form>
</template>


<script>
import UiButton from "@/Shared/UI/Button";

export default {
  props: ["model"],
  emits: ["submitted"],

  components: {
    UiButton,
  },

  computed: {
    fields() {
      return Object.keys(this.model).map((key) => {
        return {
          key,
          label: key.replaceAll("_", " "),
        };
      });
    },
  },

  methods: {
    submit() {
      this.$emit("submitted", this.values);
    },
  },

  data() {
    return {
      values: this.model,
    };
  },
};
</script>
