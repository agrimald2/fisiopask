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
        class="mt-8 max-w-3xl mx-auto px-4"
        v-if="enableSearch"
      >

        <template v-if="enableDoctorSearch">
          <div class="filter_space px-3 border cursor-pointer hover:bg-gray-100 border-l-transparent bg-white grid items-center rounded-l-r-lg">
            <button @click.prevent.self="toggleDropDownDoctors()" style="font-size:1.15rem"> Filtro Doctores <span v-show="docFilterQuery" style="font-size:1rem; font-weight:bold"> -  {{docFilterQuery}} </span> </button>
            <div v-if="this.showDropDownDoctors">
              <input type="text" placeholder="Buscar" id="myInput" v-model="docFilterQuery" style="width:100%" class="flex-grow border-gray-300 focus:outline-none focus:ring-0 focus:border-gray-300 rounded-l-lg px-4 py-3">
              <div v-for="(doctor, index) in filteredDoctors" :key="index" class="doctors_display rounded-r-lg .rounded-l-lg">
                <div @click.prevent.self="selectDropDownDoctor(doctor)"> {{doctor.name}} {{doctor.lastname}} </div>
              </div>
            </div>
          </div>
        </template>
        <form @submit.prevent="onSearch">
          <div class="flex items-stretch">
            <template v-if="enableOfficeSearch">
              <div class="filter_space px-3 border cursor-pointer hover:bg-gray-100 border-l-transparent bg-white grid items-center rounded-l-r-lg">
                <button @click.prevent.self="toggleDropDownOffices()" class="px-3 cursor-pointer hover:bg-gray-100 bg-white items-center rounded-l-r-lg" style="font-size:1.2rem">
                   Sucursal
                   <br>
                   <span style="font-size:1rem; font-weight:bold">
                   {{officeQuery}} 
                   </span>
                </button>
                <div v-if="this.showDropDownOffices">
                  <div v-for="(office, index) in offices" :key="index" class="doctors_display rounded-r-lg .rounded-l-lg">
                    <div @click.prevent.self="selectDropDownOffice(office)"> {{office.name}} </div>
                  </div>
                </div>
              </div>
            </template>

            <input
              type="text"
              class="flex-grow border-gray-300 focus:outline-none focus:ring-0 focus:border-gray-300 rounded-l-lg px-4 py-3"
              placeholder="Filtro de busqueda..."
              v-model="searchQuery"
            >
          </div>
          <div class="flex items-stretch">

            <input
              v-if="enableDateSearch"
              type="date"
              class="flex-grow border-gray-300 focus:outline-none focus:ring-0 focus:border-gray-300 px-4 py-3"
              v-model="dateQueryFrom"
            >
            <input
              v-if="enableDateSearch"
              type="date"
              class="flex-grow border-gray-300 focus:outline-none focus:ring-0 focus:border-gray-300 px-4 py-3"
              v-model="dateQueryTo"
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

<style>
  .filter_space{
    --tw-border-opacity: 1;
    border-color: rgba(209, 213, 219, var(--tw-border-opacity));
  }
  .filter_space button {
    padding: 5px
  }
  .doctors_display{
    margin-top: 5px;
    margin-bottom: 5px;
    font-size: 1.2rem;
    border: 1px solid;
    border-bottom-left-radius: 0.5rem;
    border-top-left-radius: 0.5rem;
    border-color: rgba(209, 213, 219, var(--tw-border-opacity));
    padding: 5px;
  }

  .bg-status-1{
    background-color: aquamarine;
}

.bg-status-2{
    background-color: brown;
}


.bg-status-3{
    background-color: darkcyan;
}


.bg-status-4{
    background-color: crimson;
}

  
</style>
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
    offices: null,
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
      if(this.parameters.hasOwnProperty("dateQueryFrom")) this.dateQueryFrom = this.parameters.dateQueryFrom;
      if(this.parameters.hasOwnProperty("dateQueryTo")) this.dateQueryTo = this.parameters.dateQueryTo;
      if(this.parameters.hasOwnProperty("doctorQuery")) this.doctorQuery = this.parameters.doctorQuery;
    }
  },

  data() {
    return {
      searchQuery: null,
      dateQueryFrom: null,
      dateQueryTo: null,
      doctorQuery: null,
      officeQuery: null,

      showDropDownDoctors: false,
      showDropDownOffices: false,
      docFilterQuery: null,
      docName: null,
    };
  },

  computed: {
    filteredDoctors() {
      if(this.docFilterQuery == null || this.docFilterQuery == '') return this.doctors;

      const filter = this.docFilterQuery.toLowerCase().trim();
      return this.doctors.filter(function(doc) {
        if(doc.name.toLowerCase().trim().includes(filter) || doc.lastname.toLowerCase().trim().includes(filter)) return true;
        return false;
      });
    }
  },

  methods: {
    onSearch() {
      const searchQuery = this.searchQuery;
      const dateQueryFrom = this.dateQueryFrom;
      const dateQueryTo = this.dateQueryTo;
      const doctorQuery = this.doctorQuery;
      const officeQuery = this.officeQuery;
      const data = { searchQuery, dateQueryFrom, dateQueryTo, doctorQuery, officeQuery };
      this.$inertia.get("", data, { preserveScroll: true });
    },
    toggleDropDownDoctors() {
      this.showDropDownDoctors = !this.showDropDownDoctors;
    },
    toggleDropDownOffices() {
      this.showDropDownOffices = !this.showDropDownOffices;
    },
    selectDropDownDoctor($doc) {
      this.doctorQuery = $doc.id;
      this.docFilterQuery = $doc.name + ' ' + $doc.lastname;
      this.showDropDownDoctors = false;
    },
    selectDropDownOffice($office) {
      this.officeQuery = $office.name;
      this.showDropDownOffices = false;
    },
  },
};
</script>
