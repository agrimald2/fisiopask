<template>
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
            <template v-if="enableDoctorSearch">
              <button @click.prevent.self="toggleDropDown()" class="px-3 border cursor-pointer hover:bg-gray-100 border-l-transparent bg-white flex items-center rounded-l-r-lg"> Doctores </button>
              <div v-if="this.showDropDown">
                <input type="text" placeholder="Buscar" id="myInput" v-model="docFilterQuery">
                <div v-for="(doctor, index) in filteredDoctors" :key="index">
                  <div @click.prevent.self="selectDropDown(doctor)"> {{doctor.name}} </div>
                </div>
              </div>
            </template>
            <input
              type="text"
              class="flex-grow border-gray-300 focus:outline-none focus:ring-0 focus:border-gray-300 rounded-l-lg px-4 py-3"
              placeholder="Filtro de busqueda..."
              v-model="searchQuery"
            >
            <input
              v-if="enableDateSearch"
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
    doctors: null,
    // Options
    enableSearch: {
      default: true,
      type: Boolean,
    },
    enableDateSearch: {
      default: false,
      type: Boolean,
    },
    enableDoctorSearch: {
      default: false,
      type: Boolean,
    },
    enableOfficeSearch: {
      default: false,
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
      doctorQuery: null,

      showDropDown: false,
      docFilterQuery: null,
      docName: null,
    };
  },

  computed: {
    filteredDoctors() {
      if(this.docFilterQuery == null || this.docFilterQuery == '') return this.doctors;

      const filter = this.docFilterQuery.toLowerCase().trim();
      return this.doctors.filter((doc) => doc.name.toLowerCase().trim().includes(filter));
    }
  },

  methods: {
    onSearch() {
      const searchQuery = this.searchQuery;
      const dateQuery = this.dateQuery;
      const doctorQuery = this.doctorQuery;
      const data = { searchQuery, dateQuery, doctorQuery};
      this.$inertia.get("", data, { preserveScroll: true });
    },
    toggleDropDown() {
        this.showDropDown = !this.showDropDown;
    },
    selectDropDown($doc) {
      this.doctorQuery = $doc.id;
      this.docFilterQuery = $doc.name;
      this.showDropDown = false;
    },
  },
};
</script>
