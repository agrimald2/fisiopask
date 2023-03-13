<template>
  <div
    id="filters"
    class="flex justify-between items-center pb-2 grid grid-cols-1 sm:grid-cols-12 md:grid-cols-12 gap-2 card px-2"
  >
    <div class="relative col-span-5">
      <h5>Fec. Inicio</h5>
      <div class="relative max-w-sm">
        <div
          class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none"
        >
          <svg
            aria-hidden="true"
            class="w-5 h-5 text-gray-500"
            fill="currentColor"
            viewBox="0 0 20 20"
            xmlns="http://www.w3.org/2000/svg"
          >
            <path
              fill-rule="evenodd"
              d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z"
              clip-rule="evenodd"
            ></path>
          </svg>
        </div>
        <input
          type="date"
          class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5"
          placeholder="Select date"
          v-model="startDate"
        />
      </div>
    </div>
    <div class="relative col-span-5">
      <h5>Fec. Final</h5>
      <div class="relative max-w-sm">
        <div
          class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none"
        >
          <svg
            aria-hidden="true"
            class="w-5 h-5 text-gray-500"
            fill="currentColor"
            viewBox="0 0 20 20"
            xmlns="http://www.w3.org/2000/svg"
          >
            <path
              fill-rule="evenodd"
              d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z"
              clip-rule="evenodd"
            ></path>
          </svg>
        </div>
        <input
          type="date"
          class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5"
          placeholder="Select date"
          v-model="endDate"
        />
      </div>
    </div>
    <div class="relative col-span-2 center">
      <button
        @click="search()"
        type="button"
        class="pb-2 mb-2 inline-block px-6 py-2.5 bg-gray-700 text-white font-medium text-xs leading-tight uppercase rounded shadow-md hover:bg-gray-900 hover:shadow-lg focus:bg-gray-900 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-gray-900 active:shadow-lg transition duration-150 ease-in-out"
      >
        FILTRAR
      </button>

      <button
        @click="clearFilters()"
        type="button"
        class="pb-2 mb-2 ml-4 inline-block px-6 py-2.5 bg-white-700 text-black font-medium text-xs leading-tight uppercase rounded shadow-md transition duration-150 ease-in-out"
      >
        X
      </button>
    </div>
  </div>
  <hr class="mb-2" />
</template>
<script>
export default {
  props: [],
  data() {
    return {
      startDate: this.todayDate,
      endDate: this.todayDate,
    };
  },
  methods: {
    search() {
      axios
        .get("/dashboard/filterStadistics/bills", {
          params: {
            start_date: this.startDate,
            end_date: this.endDate,
          },
        })
        .then((response) => {
          this.$emit("filterGraphs", response.data);
        })
        .catch((error) => {
          console.error(error);
        });
    },

    clearFilters() {
      location.reload();
      console.log("reloading");
    },
  },
};
</script>
