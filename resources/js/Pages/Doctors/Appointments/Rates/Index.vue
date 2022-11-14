<template>
    <app-layout title="Tarifas de la Cita">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Cita de {{appointment.patient.fullname}} / {{appointment.date}} a las {{appointment.start}}
            </h2>
        </template>

        <!--

        <app-body>
            <div class="p-2">
                <div class="text-xl">Paciente:</div>
                Cita de <span class="text-lg">{{ appointment.patient.fullname }}</span>
                El {{ appointment.date }} de {{ appointment.start }} a {{ appointment.end }} hrs.

                <div class="mt-4 pt-4 border-t">
                    <div class="text-xl">Tarifas de la Cita:</div>
                    <div class="py-4">
                        <patient-rates-table :patientRates="patientRates" />
                    </div>
                </div>

                <div class="mt-4 text-center">
                    <ui-button
                    color="blue"
                    @click="$inertia.visit(route('doctors.appointments.ticket.index', appointment.id))"
                    >Generar Ticket</ui-button>
                </div>

            </div>
        </app-body>

    -->
        <app-body>

            <ui-checkout
                :patient="appointment.patient"
                :submitUrl="route('patients.rates.store', [appointment.patient.id,appointment.id])"
                :appointment_id = "appointment.id"
                :options="{
                    redirect: route('doctors.appointments.rates.index', appointment.id)
                }"/>


        </app-body>

    </app-layout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout.vue";
import AppBody from "@/Shared/Backend/AppBody";

import UiButton from "@/Shared/Frontend/Button";
import UiCheckout from "@/Pages/Backend/PatientRates/Components/Checkout.vue";
import PatientRatesTable from "./Components/PatientRatesTable.vue";

export default {
    props: ["appointment", "patientRates","paymentMethods"],

    components: {
        AppLayout,
        AppBody,
        UiCheckout,
        UiButton,

        PatientRatesTable,
    },

    data() {
        return {

        };
    },
};
</script>
