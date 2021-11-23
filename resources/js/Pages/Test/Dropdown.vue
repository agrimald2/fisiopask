<template>
  <div class="p-8">
    <div class="text-4xl">Vue Dropdown</div>

    <div class="mt-4 mx-auto w-1/2">
      <ui-dropdown
        v-model="input"
        @changed="onChanged"
        :apiUrl="route('test.doctors')"
      />
    </div>

    <div class="mt-4">
      <pre class="bg-gray-900 text-white p-3 rounded">{{ input }}</pre>
    </div>
  </div>
</template>


<script>
import UiDropdown from "@/Shared/Dropdown/Dropdown.vue";

export default {
  components: {
    UiDropdown,
  },

  data() {
    return {
      input: 9,
      options: [
        "Blanco",
        "Negro",
        "Gris",
        "Azul",
        "Naranja",
        "Rojo",
        "Beige",
        "Morado",
        "Purpura",
        "Azul cielo",
        "Azul marino",
        "Verde claro",
      ],
    };
  },

  methods: {
    onChanged(value) {
      console.log("EVENTO: ", value);
    },
  },
};
</script>
