<template>
  <Layout>
    <form @submit.prevent="onSubmit">
      <ui-container>
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
import UiContainer from "../../Frontend/BookAppointment/UI/Container.vue";
import UiLoading from "../../Frontend/BookAppointment/UI/Loading.vue";
import UiButton from "../../Frontend/BookAppointment/UI/Button.vue";
import UiError from "../../Frontend/BookAppointment/UI/Error.vue";
import UiInput from "../../Frontend/BookAppointment/UI/Input.vue";
import UiRadio from "../../Frontend/BookAppointment/UI/Radio.vue";
import Layout from "../../Frontend/BookAppointment/Layout/Layout.vue";

export default {
  props: ["appointment", "officeOptions"],

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
      
      office_id: null,

      form: this.$inertia.form({
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

        this.office_id = this.form.office_id;

        const url = route('reschedule.postOffice', [this.appointment, this.office_id]);

        const data = {};

        this.$inertia.post(url, data, {
                onFinish: () => {
                    this.loading = false;
                    this.datePickerInput = null;
                }
            });
    },
  },
};
</script>
