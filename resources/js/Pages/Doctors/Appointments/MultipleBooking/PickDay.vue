<template>
    <Layout>
        <form @submit.prevent="datePickerModel(this.datePickerInput)">
          <ui-container>
            <!-- Title -->
            <div class="text-center">
              Programar cita de {{ patient.name }}
            </div>
            <div class="text-center text-2xl uppercase tracking-wider text-gray-800">
              Elija el Día
            </div>

            <!-- Calendar -->
            <div
              class="mt-8"
              v-show="!loading"
            >
              <DatePicker
                color="green"
                :rows="1"
                is-expanded
                :min-date="new Date()"
                v-model="datePickerInput"
                locale="es"
              />
              <ui-error :message="$page.props.errors.date" />
            </div>

            <!-- Submit -->
            <ui-loading
              v-if="loading"
              class="py-8"
            ></ui-loading>
            <div
              class="mt-8 hidden text-center grid"
              v-else
            >
              <ui-button>
                <div class="flex items-center justify-center">
                  Siguiente
                  <i class="fas fa-angle-right pl-3"></i>
                </div>
              </ui-button>
            </div>

            <div class="mt-8 text-center grid">
                <ui-button
                    type="button"
                    class="bg-red-500 border-red-700 text-white"
                    @click="$inertia.visit(route('doctors.appointments.index'))"
                >
                    <div class="flex items-center justify-center">
                        <i class="fas fa-angle-left pr-3 bold uppercase"></i>
                        Volver a Citas
                    </div>
                </ui-button>
            </div>

          </ui-container>
        </form>
    </Layout>
</template>

<script>
import UiContainer from "../../../Frontend/BookAppointment/UI/Container.vue";
import UiLoading from "../../../Frontend/BookAppointment/UI/Loading.vue";
import UiButton from "../../../Frontend/BookAppointment/UI/Button.vue";
import UiError from "../../../Frontend/BookAppointment/UI/Error.vue";
import Layout from "../../../Frontend/BookAppointment/Layout/Layout.vue";

import { DatePicker } from "v-calendar";
import dates from "@/ui/dates.js";

export default {
    props: ['patient'],

    components: {
        UiContainer,
        UiLoading,
        UiButton,
        UiError,

        Layout,

        DatePicker,
    },

    data() {
        return {
            loading: false,

            datePickerInput: null,
        };
    },

    watch: {
        datePickerInput(value) {
            if(value) {
                this.submitFormWithDate(value);
            }
        }
    },

    methods: {
        submitFormWithDate(datePickerModel) {
            this.loading = true;

            const url = route('multipleBooking.postDay', this.patient);

            const date = dates.dateForLaravel(datePickerModel);
            const data = {date};

            this.$inertia.post(url, data, {
                onFinish: () => {
                    this.loading = false;
                    this.datePickerInput = null;
                }
            });
        }
    }
}
</script>