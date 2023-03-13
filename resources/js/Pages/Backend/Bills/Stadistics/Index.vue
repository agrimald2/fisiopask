<template>
  <app-layout>
    <div class="py-2">
      <div class="max-w-7xl mx-auto xs:px-2 sm:px-2 lg:px-2 mb-2">
        <div class="overflow-hidden px-2">
          <h2 class="my-6 text-lg m:px-4 lg:px-6 font-semibold text-gray-700">
            Gráficos de Gastos
          </h2>
          <div
            class="grid sm:px-4 lg:px-6 gap-6 mb-2 md:grid-cols-1 min-w-0 p-4 bg-white rounded-lg shadow-xs"
          >
            <Filters @filterGraphs="filterGraphs" />
          </div>
          <div class="grid sm:px-4 lg:px-6 gap-6 mb-2 md:grid-cols-2">
            <div class="min-w-0 p-4 bg-white rounded-lg shadow-xs">
              <div class="justify-between">
                <h2 class="font-bold tx-md uppercase">Gastos en Soles x Mes</h2>
                <div id="chart">
                  <apexchart
                    type="bar"
                    :options="monthlyBillsOptions"
                    :series="monthlyBillsSeries"
                    height="430"
                  />
                </div>
              </div>
            </div>
          </div>
          <br />
          <div class="grid sm:px-4 lg:px-6 gap-6 mb-2 md:grid-cols-1">
            <div class="min-w-0 p-4 bg-white rounded-lg shadow-xs">
              <div class="justify-between">
                <h2 class="font-bold tx-md uppercase">
                  Gastos en Soles x SubFamilias
                </h2>
                <div id="chart">
                  <apexchart
                    :key="chartKey"
                    type="bar"
                    :options="amountsBySubFamiliesOptionsKeyed"
                    :series="amountsBySubFamiliesSeriesKeyed"
                    height="430"
                  />
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </app-layout>
</template>
<script>
import AppLayout from "@/Layouts/AppLayout.vue";
import JetInput from "@/Jetstream/Input";
import JetButton from "@/Jetstream/Button";
import axios from "axios";
import Dropdown from "@/Shared/Dropdown/Dropdown";
import VueApexCharts from "vue-apexcharts";
import ApexCharts from "apexcharts";
import Filters from "./Filters.vue";
import { Vue } from "vue";

export default {
  props: ["months", "amounts", "subFamilies", "amountsBySubFamilies"],
  components: {
    AppLayout,
    JetInput,
    JetButton,
    Dropdown,
    VueApexCharts,
    Filters,
  },
  data() {
    return {
      categories: this.months,
      data: this.amounts,

      subFamilies: this.subFamilies,
      amountsBySubFamilies: this.amountsBySubFamilies,

      filteredSubFamilies: Array,
      filteredAmountsBySubFamilies: Array,

      chartKey: 0,
    };
  },
  computed: {
    monthlyBillsOptions() {
      return {
        chart: {
          type: "bar",
        },
        plotOptions: {
          bar: {
            horizontal: false,
            endingShape: "rounded",
            distributed: false,
          },
        },
        dataLabels: {
          enabled: true,
        },
        xaxis: {
          categories: this.categories,
        },
        yaxis: {
          title: {
            text: "Gasto en S/. x Mes",
          },
        },
      };
    },
    monthlyBillsSeries() {
      return [
        {
          name: "Amount",
          data: this.data,
        },
      ];
    },
    /*
        amountsBySubFamiliesOptions() {
        return {
            chart: {
            type: "bar",
            },
            plotOptions: {
            bar: {
                horizontal: false,
                endingShape: "rounded",
                distributed: true,
            },
            },
            dataLabels: {
            enabled: true,
            },
            xaxis: {
            categories: this.subFamilies,
            },
            yaxis: {
            title: {
                text: "Gasto en S/. x Subfamilias",
            },
            },
        };
        },

        amountsBySubFamiliesSeries() {
        return [
            {
            name: "Amount",
            data: this.amountsBySubFamilies,
            },
        ];
        },

    */

    amountsBySubFamiliesSeriesKeyed() {
      // return a new array that is dependent on amountsBySubFamilies and chartKey
      return [
        {
          name: "Amount",
          data: this.amountsBySubFamilies,
          key: this.chartKey,
        },
      ];
    },

    amountsBySubFamiliesOptionsKeyed() {
      return {
        chart: {
          type: "bar",
        },
        plotOptions: {
          bar: {
            horizontal: false,
            endingShape: "rounded",
            distributed: true,
          },
        },
        dataLabels: {
          enabled: true,
        },
        xaxis: {
          categories: this.subFamilies,
        },
        yaxis: {
          title: {
            text: "Gasto en S/. x Subfamilias",
          },
        },
        key: this.chartKey,
      };
    },
  },
  methods: {
    filterGraphs(data) {
      this.filteredSubFamilies = data.subFamilies;
      this.filteredAmountsBySubFamilies = data.amountsBySubFamilies;

      this.subFamilies.splice(
        0,
        this.subFamilies.length,
        ...this.filteredSubFamilies
      );
      this.amountsBySubFamilies.splice(
        0,
        this.amountsBySubFamilies.length,
        ...this.filteredAmountsBySubFamilies
      );

      console.table(this.amountsBySubFamilies);

      // update the key to force a re-render of the chart
      this.chartKey += 1;
    },
  },
};
</script>
