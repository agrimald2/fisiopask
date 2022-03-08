<template>
  <div class="grid gap-4">
    <div class="bg-white">
      <div class="font-bold">Familia</div>
      <dropdown
        v-model="input.family_id"
        :options="Object.fromEntries(families.map((x) => [x.id, x.name]))"
        @changed="getSubfamilies"
        searchable
      />
    </div>

    <div class="grid">
      <div class="font-bold">Subfamilia</div>
      <dropdown
        v-model="input.subfamily_id"
        :options="Object.fromEntries(subfamilies.map((x) => [x.id, x.name]))"
        @changed="getRates"
        searchable
      />
    </div>

    <div class="grid">
      <div class="font-bold">Tarifa</div>
      <dropdown
        v-model="input.rate_id"
        :options="Object.fromEntries(rates.map((x) => [x.id, '$'+x.price+' - '+x.name]))"
        @changed="selectRate"
        searchable
      />
    </div>
  </div>
</template>


<script>
import axios from "axios";
import Dropdown from "@/Shared/Dropdown/Dropdown";

export default {
  props: ["modelValue"],
  emits: ["update:modelValue"],

  components: {
    Dropdown,
  },

  mounted() {
    const url = route("productSelect.families");
    axios.get(url).then((res) => (this.families = res.data));
  },

  methods: {
    getSubfamilies(id) {
      const url = route("productSelect.subfamilies", id);
      axios.get(url).then((res) => (this.subfamilies = res.data));
      this.input.subfamily_id = null;
    },

    getRates(id) {
      const url = route("productSelect.rates", id);
      axios.get(url).then((res) => (this.rates = res.data));
      this.input.rate_id = null;
    },

    selectRate(id) {
      const product = this.rates.find((x) => x.id == id);

      if (product) {
        this.$emit("update:modelValue", product);
      }
    },
  },

  data() {
    return {
      families: [],
      subfamilies: [],
      rates: [],

      input: {
        family_id: null,
        subfamily_id: null,
        rates_id: this.modelValue,
      },
    };
  },
};
</script>
