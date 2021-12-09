x<template>
  <app-layout title="Dashboard">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ title }}
      </h2>
    </template>

    <div class="py-6 sm:px-3">

      <div
        class="text-center"
        v-if="create"
      >
        <div
          class="border inline-block bg-gray-900 text-white rounded px-2 py-1 cursor-pointer hover:scale-105 transform transition-transform"
          @click="$inertia.visit(create)"
        >Añadir nuevo</div>
      </div>

      <!-- Search bar -->
      <div
        class="mt-8 max-w-2xl mx-auto px-4"
        v-if="enableSearch"
      >
        <form @submit.prevent="onSearch">
          <div class="flex items-stretch">
            <input
              type="text"
              class="flex-grow border-gray-300 focus:outline-none focus:ring-0 focus:border-gray-300 rounded-l-lg px-4 py-3"
              placeholder="Filtro de busqueda..."
              v-model="searchQuery"
            >
            <input
              type="date"
              class="flex-grow border-gray-300 focus:outline-none focus:ring-0 focus:border-gray-300 px-4 py-3"
              v-model="dateQuery"
            >
            <button
              type="submit"
              class="px-3 border cursor-pointer hover:bg-gray-100 border-l-transparent bg-white flex items-center rounded-r-lg"
            >
              <i class="fas fa-search"></i>
            </button>
          </div>
        </form>
      </div>

      <div class="xl:px-12 mx-auto mt-6">
        <gridie
          class="text-left bg-white overflow-x-auto w-full"
          :cols="cols"
          :rows="rows"
        />
      </div>

      <div
        v-if="links"
        class="px-4 mt-12 flex justify-center"
      >
        <pagination :links="links" />
      </div>
    </div>

  </app-layout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout.vue";
import Pagination from "@/Shared/Pagination.vue";

import { Gridie, cells } from "@ferchoposting/gridie";

export default {
  props: {
    model: null,
    create: null,
    grid: null,
    title: null,
    links: null,
    parameters: null,
    // Options
    enableSearch: {
      default: true,
      type: Boolean,
    },
  },

  components: {
    AppLayout,
    Gridie,
    Pagination,
  },

  setup(props, context) {
    const grid = require(`../../${props.grid}`).default;
    return grid(props, context);
  },

  mounted() {
    if (this.parameters)
    { 
      if(this.parameters.hasOwnProperty("searchQuery")) this.searchQuery = this.parameters.searchQuery;
      if(this.parameters.hasOwnProperty("dateQuery")) this.dateQuery = this.parameters.dateQuery;
    }
  },

  data() {
    return {
      searchQuery: null,
      dateQuery: null,
    };
  },

  methods: {
    onSearch() {
      const searchQuery = this.searchQuery;
      const dateQuery = this.dateQuery;
      const data = { searchQuery, dateQuery};
      this.$inertia.get("", data, { preserveScroll: true });
    },
  },
};
</script>
