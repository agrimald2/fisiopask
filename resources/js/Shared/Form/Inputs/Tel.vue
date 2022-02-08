<template>
  <div>
    <!-- Input -->
    <vue-tel-input
      v-model="input"
      mode="international"
      defaultCountry="pe"
      :inputOptions="{ placeholder: 'NÚMERO DE CELULAR', styleClasses:'py-3', type: 'tel' }"
      validCharactersOnly
      :preferredCountries="['pe', 'mx', 'cl', 'ar', 've', 'co', 'us']"
      required
      @input="onInputVueLegacy"
      @on-input="onInput"
    ></vue-tel-input>
    <!-- Number -->
    <div class="mt-2 text-xl text-center">
      {{ number }}
    </div>
    <!-- Country -->
    <div class="text-sm text-center">
      {{ country }}
    </div>

  </div>
</template>

<script>
import { VueTelInput } from "vue-tel-input";
import "vue-tel-input/dist/vue-tel-input.css";

export default {
  props: ["modelValue"],
  emits: ["update:modelValue"],

  components: {
    VueTelInput,
  },

  data() {
    return {
      input: new String(this.modelValue).toString(),
      country: null,
      number: null,
    };
  },

  methods: {
    onInput(value, { number, country }) {
      this.number = number;
      this.country = country ? country.name : null;

      this.$emit("update:modelValue", this.onlyNumbers(number));
    },

    onInputVueLegacy(obj) {
      const input = obj.path[0].value;

      obj.path[0].value = this.onlyNumbers(input);
    },

    onlyNumbers(value) {
      if (!value) return value;

      return value.replaceAll(/\D/g, "");
    },
  },
};
</script>
