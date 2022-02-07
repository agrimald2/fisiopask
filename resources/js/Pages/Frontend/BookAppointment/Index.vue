<template>
  <Layout>
    <form @submit.prevent="onSubmit">
      <ui-container>
        <!-- DNI -->
        <div>
          <div class="text-center text-4xl uppercase tracking-wider text-gray-800">
            DNI
          </div>

          <div class="mt-4">
            <ui-input
              v-model="form.dni"
              type="tel"
              required
              placeholder="Número DNI o C.E."
              :disabled="loading"
            />
            <ui-error
              class="mt-2"
              :message="form.errors.dni"
            />
          </div>
        </div>

        <!-- office_id -->
        <div
          class="mt-8"
          v-show="Object.keys(officeOptions).length > 1"
        >
          <div class="text-center text-4xl uppercase tracking-wider text-gray-800">
            Sucursal
          </div>

          <div class="mt-4">
            <ui-radio
              class="flex-col"
              :options="officeOptions"
              v-model="form.office_id"
            />

            <ui-error
              class="mt-2"
              :message="form.errors.office_id"
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
            <div class="flex items-center justify-center" style="font-size:1.2rem">
              SIGUIENTE
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

export default {
  props: ["dni", "officeOptions"],

  components: {
    Layout,

    UiContainer,

    UiInput,
    UiRadio,
    UiError,
    UiButton,

    UiLoading,
  },

  mounted() {
    this.autoSelectOffice(this.officeOptions);
  },

  data() {
    return {
      loading: false,

      form: this.$inertia.form({
        dni: this.dni,
        office_id: null,
      }),
    };
  },

  methods: {
    autoSelectOffice(officeOptions) {
      const keys = Object.keys(officeOptions);

      const key = keys[0];

      this.form.office_id = key;
    },

    onSubmit() {
      this.loading = true;
      this.form.post(route("bookAppointment.index.post"), {
        onFinish: () => (this.loading = false),
      });
    },
  },
};
</script>
