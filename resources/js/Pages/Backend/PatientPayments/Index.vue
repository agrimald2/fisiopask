<template>
    <app-layout title="Pagos Recibidos">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Pagos Recibidos
            </h2>
        </template>
        <div class="mt-4 sm:px-2 md:px-3 lg:px-4 overflow-x-auto">
            <div class="max-w-7xl mx-auto py-5 sm:px-6 lg:px-8 bg-white text-center">
                <div class="grid grid-cols-4 gap-4">
                    <div class="col-span-4 md:col-span-1">
                        <select v-model="payment_method_id"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                            <option selected value="">Método de pago</option>
                            <option class="text-black" v-for="payment_method in payment_methods" :value="payment_method.id">
                                {{ payment_method.payment_method }}</option>
                        </select>
                    </div>
                    <div class="col-span-2 md:col-span-1">
                        <div class="relative" data-te-datepicker-init data-te-input-wrapper-init>
                            <input type="date" v-model="start_date"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                placeholder="Select a date" />
                        </div>
                    </div>
                    <div class="col-span-2 md:col-span-1">
                        <div class="relative" data-te-datepicker-init data-te-input-wrapper-init>
                            <input type="date" v-model="end_date"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                placeholder="Select a date" />
                        </div>
                    </div>
                    <div class="col-span-5 md:col-span-1">
                        <div class="relative mb-4 flex w-full flex-wrap items-stretch">
                            <input type="search" v-model="search_query"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                placeholder="Search" aria-label="Search" aria-describedby="button-addon2" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="sm:px-2 md:px-3 lg:px-4 overflow-x-auto">
            <div class="max-w-7xl mx-auto py-2.5 sm:px-6 lg:px-8 text-center">
                <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                    <button type="button" @click="fetchPatientPayments()"
                        class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">Buscar</button>
                    <button type="button" @click="exportToExcel(patient_payments)"
                        class="text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2">Excel</button>
                </div>
            </div>
        </div>

        <div class="mt-4 sm:px-2 md:px-3 lg:px-4 overflow-x-auto">
            <div class="max-w-7xl mx-auto py-5 sm:px-6 lg:px-8 bg-white text-center">
                <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                    <ul class="mb-2 hidden text-sm font-medium text-center text-gray-500 divide-x divide-gray-200 rounded-lg sm:flex"
                        id="fullWidthTab" data-tabs-toggle="#fullWidthTabContent" role="tablist">
                        <li class="w-full" v-for="totalAmountMethod in totalAmountPerMethod">
                            <button id="stats-tab" data-tabs-target="#stats" type="button" role="tab" aria-controls="stats"
                                aria-selected="true"
                                class="inline-block w-full p-4 rounded-tl-lg bg-gray-50 hover:bg-gray-100 focus:outline-none">
                                {{ totalAmountMethod.name }} <br>
                                <span class="text-black font-bold">
                                    S/{{ totalAmountMethod.total.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',') }} </span>
                            </button>
                        </li>
                        <li class="w-full">
                            <button id="about-tab" data-tabs-target="#about" type="button" role="tab" aria-controls="about"
                                aria-selected="false"
                                class="inline-block w-full p-4 bg-gray-50 hover:bg-gray-100 focus:outline-none">
                                Pagos <br>
                                <span class="text-black font-bold">{{ totalResults }}</span></button>
                        </li>
                        <li class="w-full">
                            <button id="faq-tab" data-tabs-target="#faq" type="button" role="tab" aria-controls="faq"
                                aria-selected="false"
                                class="inline-block w-full p-4 rounded-tr-lg bg-gray-50 hover:bg-gray-100 focus:outline-none">
                                Total Soles <br>
                                <span class="text-black font-bold">S/ {{
                                    totalAmount.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',') }}</span></button>
                        </li>
                    </ul>
                    <span v-if="!patient_payments" class="loader"></span>
                    <table v-else class="w-max md:w-full text-sm text-left text-gray-500">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3">
                                    #
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Paciente
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Monto
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Tarifa
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Fecha | Hora
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(patient_payment, index) in patient_payments"
                                class="bg-white border-b hover:bg-gray-50">
                                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                    {{ index + 1 }}
                                </th>
                                <td class="px-6 py-4">
                                    {{ patient_payment.lastname1 }} {{ patient_payment.lastname2 }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ patient_payment.ammount }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ patient_payment.name }} <br>
                                    <strong> {{ patient_payment.payment_method }} </strong>
                                </td>
                                <td class="px-6 py-4">
                                    <p>
                                        <span v-if="patient_payment.payment_author">{{ patient_payment.payment_author
                                        }}</span>
                                        <span v-else>No especifica</span>
                                    </p>
                                    <a href="#" class="font-medium text-blue-600 hover:underline">
                                        {{ new Date(patient_payment.payment_created_at).toLocaleDateString() }}
                                        |
                                        {{ new Date(patient_payment.payment_created_at).toLocaleString('en-US', {
                                            hour12: false, hour: '2-digit',
                                            minute: '2-digit'
                                        }) }}
                                    </a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </app-layout>
</template>
  
<script>
import AppLayout from "@/Layouts/AppLayout.vue";
import JetSecondaryButton from "@/Jetstream/SecondaryButton";
import axios from "axios";
import dates from "@/ui/dates";

export default {
    props: ['payment_methods'],
    components: {
        AppLayout,
        JetSecondaryButton,
    },
    data() {
        return {
            payment_method_id: null,
            start_date: dates.todayString(),
            end_date: dates.todayString(),
            search_query: null,
            patient_payments: null,
            totalResults: 0,
            totalAmount: 0,
            totalAmountPerMethod: [],
        };
    },
    computed: {

    },
    mounted() {
        this.fetchPatientPayments();
    },
    methods: {
        fetchPatientPayments() {
            this.patient_payments = null;
            axios.get('/filterPatientPayments', {
                params: {
                    payment_method_id: this.payment_method_id,
                    start_date: this.start_date,
                    end_date: this.end_date,
                    search_query: this.search_query,
                }
            })
                .then(response => {
                    this.patient_payments = response.data.patientPayments;
                    this.totalResults = response.data.totalResults;
                    this.totalAmount = response.data.totalAmount;
                    this.totalAmountPerMethod = response.data.totalAmountPerMethod;
                })
                .catch(error => {
                    console.log(error);
                });
        },
        organizePayments(data) {
            let desorganizedData = data;
            const newArray = [];
            for (let i = 0; i < desorganizedData.length; i++) {
                const newObj = {
                    ID: i + 1,
                    Paciente: desorganizedData[i].lastname1 + ' ' + desorganizedData[i].lastname2,
                    Monto: desorganizedData[i].ammount,
                    Tarifa: desorganizedData[i].name,
                    Fecha: new Date(desorganizedData[i].payment_created_at).toLocaleDateString(),
                    Hora: new Date(desorganizedData[i].payment_created_at).toLocaleString('en-US', { hour12: false, hour: '2-digit', minute: '2-digit' }),
                    Autor: desorganizedData[i].payment_author,
                    Metodo: desorganizedData[i].payment_method,
                };
                newArray.push(newObj);
            }
            return newArray;
        },
        exportToExcel(patient_payments) {
            const exportPayments = this.organizePayments(patient_payments);
            const csvData = this.toCsv(exportPayments);

            const blob = new Blob([csvData], { type: "text/csv;charset=utf-8;" });
            const url = URL.createObjectURL(blob);

            const link = document.createElement("a");
            link.setAttribute("href", url);
            link.setAttribute("download", "ReporteDePagosFisiosalud.csv");
            document.body.appendChild(link);
            link.click();
        },
        toCsv(data) {
            const delimiter = ",";
            const keys = Object.keys(data[0]);

            const headerRow = keys.join(delimiter) + "\n";
            const bodyRows = data
                .map((row) => {
                    return keys
                        .map((key) => {
                            return '"' + row[key] + '"';
                        })
                        .join(delimiter);
                })
                .join("\n");

            return headerRow + bodyRows;
        },
    }
}
</script>
<style scoped> .loader {
     width: 48px;
     height: 48px;
     border-radius: 50%;
     display: inline-block;
     border-top: 4px solid #FFF;
     border-right: 4px solid transparent;
     box-sizing: border-box;
     animation: rotation 1s linear infinite;
 }

 .loader::after {
     content: '';
     box-sizing: border-box;
     position: absolute;
     left: 0;
     top: 0;
     width: 48px;
     height: 48px;
     border-radius: 50%;
     border-left: 4px solid #FF3D00;
     border-bottom: 4px solid transparent;
     animation: rotation 0.5s linear infinite reverse;
 }

 @keyframes rotation {
     0% {
         transform: rotate(0deg);
     }

     100% {
         transform: rotate(360deg);
     }
 }
</style>