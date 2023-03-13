<template>
  <div
    id="filters"
    class="flex justify-between items-center pb-2 grid grid-cols-1 sm:grid-cols-12 md:grid-cols-12 gap-2 card px-2"
  >
    <div class="relative col-span-6">
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

    <div class="relative col-span-6">
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

    <div class="relative col-span-3">
      <h5>Familia</h5>
      <select
        id="libraries"
        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
        v-model="family"
      >
        <option selected value="0">Todas</option>
        <option
          v-for="(family, index) in families"
          :key="index"
          :value="family.id"
        >
          {{ family.name }}
        </option>
      </select>
    </div>

    <div class="relative col-span-3">
      <h5>SubFamilia</h5>
      <select
        id="libraries"
        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
        v-model="subfamily"
      >
        <option selected value="0">Todas</option>
        <option
          v-for="(subfamily, index) in subfamilies"
          :key="index"
          :value="subfamily.id"
        >
          {{ subfamily.name }}
        </option>
      </select>
    </div>

    <div class="relative col-span-3">
      <h5>Pagador</h5>
      <select
        id="libraries"
        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
        v-model="payer"
      >
        <option selected value="0">Todos</option>
        <option
          v-for="(payer, index) in payers"
          :key="index"
          :value="payer.name"
        >
          {{ payer.name }}
        </option>
      </select>
    </div>
    <div class="relative col-span-3">
      <h5>Receptor</h5>
      <select
        id="libraries"
        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
        v-model="receiver"
      >
        <option selected value="0">Todas</option>
        <option
          v-for="(receiver, index) in receivers"
          :key="index"
          :value="receiver.name"
        >
          {{ receiver.name }}
        </option>
      </select>
    </div>
  </div>

  <div class="buttons">
    <button
      @click="search()"
      type="button"
      class="pb-2 mb-2 inline-block px-6 py-2.5 bg-gray-700 text-white font-medium text-xs leading-tight uppercase rounded shadow-md hover:bg-gray-900 hover:shadow-lg focus:bg-gray-900 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-gray-900 active:shadow-lg transition duration-150 ease-in-out"
    >
      FILTRAR
    </button>

    <button
      @click="showRequests()"
      type="button"
      class="ml-2 pb-2 mb-2 inline-block px-6 py-2.5 bg-yellow-300 text-white font-medium text-xs leading-tight uppercase rounded shadow-md hover:bg-yellow-700 hover:shadow-lg focus:bg-gray-900 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-gray-900 active:shadow-lg transition duration-150 ease-in-out"
    >
      SOLICITUDES
    </button>

    <button
      @click="clearFilters()"
      type="button"
      class="pb-2 mb-2 ml-4 inline-block px-6 py-2.5 bg-white-700 text-black font-medium text-xs leading-tight uppercase rounded shadow-md transition duration-150 ease-in-out"
    >
      X
    </button>
  </div>

  <hr class="mb-2" />
</template>
<script>
export default {
  props: ["subfamilies", "families", "receivers", "payers", "todayDate"],
  data() {
    return {
      subfamily: "",
      family: "",
      receiver: "",
      payer: "",
      startDate: this.todayDate,
      endDate: this.todayDate,
    };
  },
  methods: {
    search() {
      axios
        .get("/dashboard/filtered-bills", {
          params: {
            subfamily_id: this.subfamily,
            family_id: this.family,
            receiver: this.receiver,
            payer: this.payer,
            start_date: this.startDate,
            end_date: this.endDate,
          },
        })
        .then((response) => {
          this.$emit("filterData", response.data);
        })
        .catch((error) => {
          console.error(error);
        });
    },

    showRequests() {
      axios
        .get("/dashboard/filtered-bills", {
          params: {
            subfamily_id: this.subfamily,
            family_id: this.family,
            receiver: this.receiver,
            payer: this.payer,
            start_date: this.startDate,
            end_date: this.endDate,
            showRequests: true,
          },
        })
        .then((response) => {
          this.$emit("filterData", response.data);
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
<style scoped>
/* toggle-pill-color */
.toggle-pill-color input[type="checkbox"] {
  display: none;
}
.toggle-pill-color input[type="checkbox"] + label {
  display: block;
  position: relative;
  width: 3em;
  height: 1.6em;
  margin-bottom: 20px;
  border-radius: 1em;
  background: #e84d4d;
  box-shadow: inset 0px 0px 5px rgba(0, 0, 0, 0.3);
  cursor: pointer;
  -webkit-user-select: none;
  -moz-user-select: none;
  -ms-user-select: none;
  -webkit-transition: background 0.1s ease-in-out;
  transition: background 0.1s ease-in-out;
}
.toggle-pill-color input[type="checkbox"] + label:before {
  content: "";
  display: block;
  width: 1.2em;
  height: 1.2em;
  border-radius: 1em;
  background: #fff;
  box-shadow: 2px 0px 5px rgba(0, 0, 0, 0.2);
  position: absolute;
  left: 0.2em;
  top: 0.2em;
  -webkit-transition: all 0.2s ease-in-out;
  transition: all 0.2s ease-in-out;
}
.toggle-pill-color input[type="checkbox"]:checked + label {
  background: #47cf73;
}
.toggle-pill-color input[type="checkbox"]:checked + label:before {
  box-shadow: -2px 0px 5px rgba(0, 0, 0, 0.2);
  left: 1.6em;
}
/* toggle-pill-color end */
</style>
