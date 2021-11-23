<template>
  <Layout>
    <form @submit.prevent="onSubmit">
      <ui-container>
        <!-- Title -->
        <div class="text-center text-2xl uppercase tracking-wider text-gray-800">
          Por favor confirma tu Teléfono
        </div>

        <!-- name -->
        <div class="mt-4">
          <div class="text-center font-bold uppercase tracking-wider text-gray-800">
            Nombre
          </div>

          <div class="mt-2">
            <ui-input
              :value="name"
              disabled
            />
          </div>

        </div>

        <!-- phone -->
        <div class="mt-4">
          <div class="text-center font-bold uppercase tracking-wider text-gray-800">
            Teléfono
          </div>

          <div class="mt-2">
            <ui-input-tel
              v-model="form.phone"
              required
              :disabled="loading"
            />
            <ui-error
              class="mt-2"
              :message="form.errors.phone"
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

import UiInput from "./UI/Input.vue";
import UiRadio from "./UI/Radio.vue";
import UiError from "./UI/Error.vue";
import UiButton from "./UI/Button.vue";

import UiContainer from "./UI/Container.vue";

import UiLoading from "./UI/Loading.vue";

import UiInputTel from "@/Shared/Form/Inputs/Tel";

export default {
  props: ["dni", "name"],

  components: {
    Layout,

    UiContainer,
    UiInput,
    UiRadio,
    UiError,
    UiButton,
    UiLoading,

    UiInputTel,
  },

  data() {
    return {
      loading: false,

      form: this.$inertia.form({
        phone: null,
      }),
    };
  },

  methods: {
    onSubmit() {
      this.loading = true;
      this.form.post(route("bookAppointment.patientPhone.post", this.dni), {
        onFinish: () => (this.loading = false),
      });
    },
  },
};
</script>
