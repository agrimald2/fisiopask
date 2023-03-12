<template>
  <app-layout title="Transacciones Fisiosalud">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Transacciones
      </h2>
    </template>

    <br />

    <div class="max-w-7xl mx-auto py-2 sm:px-6 lg:px-8 bg-white text-center">
      <jet-nav-link :href="route('bills.create')">
        <button
          type="button"
          class="pb-2 mb-2 inline-block px-6 py-2.5 bg-gray-800 text-white font-medium text-xs leading-tight uppercase rounded shadow-md hover:bg-gray-900 hover:shadow-lg focus:bg-gray-900 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-gray-900 active:shadow-lg transition duration-150 ease-in-out"
        >
          AÑADIR NUEVO
        </button>
      </jet-nav-link>
    </div>

    <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8 bg-white text-center">
      <div class="grid grid-cols-1 sm:grid-cols-6 md:grid-cols-6 gap-4">
        <div class="col-span-4 shadow-md">
          <h2 class="font-bold text-xl mb-2">Filtros y Búsqueda</h2>
          <div class="overflow-x-auto relative shadow-md sm:rounded-lg">
            <Filters
              :subfamilies="subfamilies"
              :families="families"
              :receivers="receivers"
              :payers="payers"
              :todayDate="todayDate"
              @filterData="handleFilteredData"
            />
            <button
              type="button"
              class="pb-2 mb-2 inline-block px-6 py-2.5 bg-green-800 text-white font-medium text-xs leading-tight uppercase rounded shadow-md hover:bg-gray-900 hover:shadow-lg focus:bg-gray-900 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-gray-900 active:shadow-lg transition duration-150 ease-in-out"
              @click="exportToExcel(bills)"
            >
              Exportar a Excel
            </button>
            <table class="w-full text-sm text-left text-gray-500">
              <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                <tr>
                  <th scope="col" class="py-3 px-6">Monto</th>
                  <th scope="col" class="py-3 px-6">Receptor</th>
                  <th scope="col" class="py-3 px-6 text-center">
                    Familia - Subfamilia
                  </th>
                  <th scope="col" class="py-3 px-6">Pagador</th>
                  <th scope="col" class="py-3 px-6">Fecha y Hora</th>
                </tr>
              </thead>
              <tbody>
                <tr
                  v-for="(bill, index) in bills"
                  :key="index"
                  class="bg-white border-b hover:bg-gray-50"
                >
                  <th
                    scope="row"
                    class="py-4 px-6 font-medium text-gray-900 whitespace-nowrap"
                  >
                    S/. {{ bill.quantity }}
                  </th>
                  <td class="py-4 px-6">{{ bill.receiver }}</td>
                  <td class="py-4 px-6 text-center">
                    {{ bill.bills_sub_family.billsfamily.name }}
                    <br />
                    - <br />
                    {{ bill.bills_sub_family.name }}
                  </td>
                  <td class="py-4 px-6">{{ bill.payer }}</td>
                  <td class="py-4 px-6">
                    {{ dates.dateForHumans(bill.created_at) }}
                  </td>
                </tr>
              </tbody>
              <tfoot>
                <div class="flex justify-center">
                  <nav aria-label="Page navigation example">
                    <ul class="flex list-style-none">
                      <li class="page-item">
                        <a
                          class="page-link relative block py-1.5 px-3 rounded border-0 bg-transparent outline-none transition-all duration-300 rounded text-gray-800 hover:text-gray-800 focus:shadow-none"
                          href="#"
                          aria-label="Previous"
                        >
                          <span aria-hidden="true">&laquo;</span>
                        </a>
                      </li>
                      <li class="page-item">
                        <a
                          class="page-link relative block py-1.5 px-3 rounded border-0 bg-transparent outline-none transition-all duration-300 rounded text-gray-800 hover:text-gray-800 hover:bg-gray-200 focus:shadow-none"
                          href="#"
                          >1</a
                        >
                      </li>
                      <li class="page-item">
                        <a
                          class="page-link relative block py-1.5 px-3 rounded border-0 bg-transparent outline-none transition-all duration-300 rounded text-gray-800 hover:text-gray-800 hover:bg-gray-200 focus:shadow-none"
                          href="#"
                          >2</a
                        >
                      </li>
                      <li class="page-item">
                        <a
                          class="page-link relative block py-1.5 px-3 rounded border-0 bg-transparent outline-none transition-all duration-300 rounded text-gray-800 hover:text-gray-800 hover:bg-gray-200 focus:shadow-none"
                          href="#"
                          >3</a
                        >
                      </li>
                      <li class="page-item">
                        <a
                          class="page-link relative block py-1.5 px-3 rounded border-0 bg-transparent outline-none transition-all duration-300 rounded text-gray-800 hover:text-gray-800 hover:bg-gray-200 focus:shadow-none"
                          href="#"
                          aria-label="Next"
                        >
                          <span aria-hidden="true">&raquo;</span>
                        </a>
                      </li>
                    </ul>
                  </nav>
                </div>
              </tfoot>
            </table>
          </div>
        </div>
        <div class="col-span-2 shadow-md">
          <Summary :SumOfSubFamilies="SumOfSubFamilies" :total="total" />
        </div>
      </div>
    </div>
  </app-layout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout.vue";
import JetSectionBorder from "@/Jetstream/SectionBorder.vue";
import Filters from "./Filters.vue";
import Summary from "./Summary.vue";
import dates from "@/ui/dates.js";
import Td from "../../../../Shared/Grid/Table/Td.vue";
import JetNavLink from "@/Jetstream/NavLink.vue";

export default {
  props: [
    "bills",
    "total",
    "SumOfSubFamilies",

    "subfamilies",
    "families",
    "receivers",
    "payers",

    "todayDate",
  ],

  data() {
    return {
      bills: this.bills,
      total: this.total,
      SumOfSubFamilies: this.SumOfSubFamilies,

      jsonData: [
        { name: "John", age: 30 },
        { name: "Alice", age: 25 },
      ],
      fields: ["name", "age"],

      filteredBills: Array,
      filteredTotal: String,
      filteredsumOfSubfamilies: Array,
    };
  },

  components: {
    AppLayout,
    JetSectionBorder,
    Filters,
    Summary,
    Td,
    JetNavLink,
  },

  methods: {
    handleFilteredData(data) {
      this.filteredBills = data.bills;
      this.filteredTotal = data.total;
      this.filteredsumOfSubfamilies = data.sumOfSubfamilies;

      this.bills = this.filteredBills;
      this.total = this.filteredTotal;
      this.SumOfSubFamilies = this.filteredsumOfSubfamilies;
    },

    organizeBill(data) {
      let desorganizedData = data;
      const newArray = [];
      for (let i = 0; i < desorganizedData.length; i++) {
        const newObj = {
          ID: i + 1,
          Fecha: dates.dateForLaravel(desorganizedData[i].created_at),
          Pagador: desorganizedData[i].payer,
          MetodoDePago: desorganizedData[i].paymentway,
          OrigenDelDinero: desorganizedData[i].moneyOrigin,
          Descripcion: desorganizedData[i].description,
          Familia: desorganizedData[i].bills_sub_family.billsfamily.name,
          SubFamilia: desorganizedData[i].bills_sub_family.name,
          Receptor: desorganizedData[i].receiver,
          Monto: desorganizedData[i].quantity,
          UsuarioDeSistema: desorganizedData[i].created_by,
        };

        newArray.push(newObj);
      }
      return newArray;
    },

    exportToExcel(bills) {
      const exportBills = this.organizeBill(bills);
      console.log("1.0. Tabla a Exportar");
      console.table(exportBills);
      console.log("1.1. JSON a Exportar");
      console.log(exportBills);
    },
  },

  mounted() {
    /*
    axios.get("/dashboard/bills").then(response => {
      this.message = response.data;
    });
    */
  },

  setup() {
    return { dates };
  },
};
</script>

<style scoped>
#libraries {
  padding: 10px 25px;
}

.relative {
}

li {
  margin-bottom: 2px;
}

i {
  font-size: 2rem;
}

.libraryStats div {
  margin-bottom: 1rem;
}

.libraryStats h2 {
  font-size: 1.3rem;
}

.ISBNFilter {
}

.ISBNFilter img {
  max-width: 60%;
  margin-left: auto;
  margin-right: auto;
  margin-top: 1rem;
}

.indications {
  padding: 7px;
}

#filters {
  padding: 5px;
}
</style>
