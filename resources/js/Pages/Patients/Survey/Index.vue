<template>
  <Layout>
    <form @submit.prevent="onSubmit">
      <ui-container>
        <!-- Title -->
        <div class="text-center text-2xl uppercase tracking-wider text-gray-800">
          Encuesta De Servicio
        </div>

        <!-- Offices -->
        <div class="mt-4" id="offices">
          <div class="text-center font-bold uppercase tracking-wider text-gray-800">
            Instalaciones
          </div>

          <StarRating @update:modelValue="this.form.office_score = $event"/>
        </div>

        <!-- Doctor -->
        <div class="mt-4" id="doctor">
          <div class="text-center font-bold uppercase tracking-wider text-gray-800">
            Doctor
          </div>

          <StarRating @update:modelValue="this.form.doctor_score = $event"/>
        </div>

        <!-- Service -->
        <div class="mt-4" id="service">
          <div class="text-center font-bold uppercase tracking-wider text-gray-800">
            Servicio
          </div>

          <StarRating @update:modelValue="this.form.service_score = $event"/>
          <ui-error
              class="mt-2"
              :message="form.errors.service_score"
          />
        </div>

        <!-- Comentario -->
        <div class="mt-4">
          <div class="text-center font-bold uppercase tracking-wider text-gray-800">
            Comentario
          </div>

          <div class="mt-2">
            <ui-input
              v-model="form.comment"
              required
              :disabled="loading"
            />
            <ui-error
              class="mt-2"
              :message="form.errors.comment"
            />
          </div>
        </div>

        <!-- Submit -->
        <ui-loading
          v-if="loading"
          class="py-8"
        ></ui-loading>
        <div
          class="mt-8 text-center grid"
          v-else
        >
          <ui-button>
            <div class="flex items-center justify-center">
              Enviar
              <i class="fas fa-angle-right pl-3"></i>
            </div>
          </ui-button>
        </div>

      </ui-container>
    </form>
  </Layout>
</template>


<script>
import Layout from "@/Pages/Frontend/BookAppointment/Layout/Layout.vue";

import UiInput from "@/Pages/Frontend/BookAppointment/UI/Input.vue";
import UiRadio from "@/Pages/Frontend/BookAppointment/UI/Radio.vue";
import UiError from "@/Pages/Frontend/BookAppointment/UI/Error.vue";
import UiButton from "@/Pages/Frontend/BookAppointment/UI/Button.vue";
import UiContainer from "@/Pages/Frontend/BookAppointment/UI/Container.vue";
import UiLoading from "@/Pages/Frontend/BookAppointment/UI/Loading.vue";
import UiInputTel from "@/Shared/Form/Inputs/Tel";

import StarRating from "./Components/StarRating.vue";

import moment from "moment";


export default {
  props: ['id'],

  components: {
    Layout,

    UiContainer,
    UiInput,
    UiRadio,
    UiError,
    UiButton,
    UiLoading,

    UiInputTel,

    StarRating,
  },

  data() {
    return {
      loading: false,

      form: this.$inertia.form({
        office_score: null,
        doctor_score: 3,
        service_score: 3,
        comment: null,
        appointment_id: null,
        survey_date: null,
      }),
    };
  },

  methods: {
    onSubmit() {
      this.loading = true;
      this.form.appointment_id = this.id;
      this.form.survey_date = moment(new Date()).format("YYYY-MM-DD");
      this.form.post(route("survey.store"), {
        onFinish: () => (this.loading = false),
      });
    },
  },
};
</script>
