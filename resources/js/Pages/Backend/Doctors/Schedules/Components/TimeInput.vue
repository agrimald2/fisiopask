<template>
  <div class="grid grid-cols-2 gap-4">
    <input-select
      :options="hours"
      v-model="input.hour"
      class="mt-1 block w-full"
    />
    <input-select
      :options="minutes"
      v-model="input.minute"
      class="mt-1 block w-full"
    />
  </div>
</template>


<script>
import InputSelect from "@/Shared/Backend/Form/Components/SelectElement";

export default {
  props: ["modelValue"],

  emits: ["update:modelValue"],

  components: {
    InputSelect,
  },

  setup() {
    // Add missing zeros
    const timefy = (x) => (x < 10 ? `0${x}` : x);
    // Add AM / PM
    const hourify = (x) =>
      x < 12 ? `${x} am` : `${x % 12 == 0 ? 12 : x % 12} pm`;

    const hours = {};
    for (let i = 0; i < 24; i++) {
      hours[i] = hourify(i);
    }

    const minutes = {};
    for (let j = 0; j <= 3; j++) {
      const x = j * 15;
      minutes[x] = timefy(x) + " mins";
    }

    return {
      hours,
      minutes,
      timefy,
    };
  },

  mounted() {
    this.parseInput(this.modelValue);
  },

  data() {
    return {
      input: {
        hour: null,
        minute: null,
      },
    };
  },

  methods: {
    parseInput(value) {
      if (!value) {
        this.input.hour = null;
        this.input.minute = null;
        return;
      }

      const splitted = value.split(":");

      this.input.hour = parseInt(splitted[0]);
      this.input.minute = parseInt(splitted.length > 1 ? splitted[1] : 0);
    },
  },

  watch: {
    input: {
      deep: true,
      handler(value) {
        let newValue = null;
        if (value.hour != null && value.minute != null) {
          // Format input.hour and input.minute to HH:SS format
          newValue = `${this.timefy(value.hour)}:${this.timefy(value.minute)}`;
        }
        this.$emit("update:modelValue", newValue);
      },
    },

    modelValue(value) {
      this.parseInput(value);
    },
  },
};
</script>
