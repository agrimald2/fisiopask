<template>
  <Layout>
    <form @submit.prevent="datePickerModel(this.datePickerInput)">
      <ui-container>
        <!-- Title -->
        <div class="text-center">
          Cita a nombre de {{ name }}
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

      </ui-container>
    </form>
  </Layout>
</template>


<script>
import Layout from "./Layout/Layout.vue";

import UiContainer from "./UI/Container.vue";
import UiLoading from "./UI/Loading.vue";
import UiButton from "./UI/Button.vue";
import UiError from "./UI/Error.vue";

import { DatePicker } from "v-calendar";
import dates from "@/ui/dates.js";

export default {
  props: ["dni", "name"],

  components: {
    Layout,

    UiContainer,
    UiLoading,
    UiButton,
    UiError,

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
      if (value) {
        this.submitFormWithDate(value);
      }
    },
  },

  methods: {
    submitFormWithDate(datePickerModel) {
      this.loading = true;

      const url = route("bookAppointment.pickDay.post", this.dni);

      const date = dates.dateForLaravel(datePickerModel);
      const data = { date };

      this.$inertia.post(url, data, {
        onFinish: () => {
          this.loading = false;
          this.datePickerInput = null;
        },
      });
    },
  },
};
</script>
