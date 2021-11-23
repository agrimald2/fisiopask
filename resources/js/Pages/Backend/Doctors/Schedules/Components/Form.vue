<template>
  <jet-form-section @submitted="onSubmitted">
    <template #title>
      Añadir un Horario
    </template>

    <template #description>
      Descripción del formulario
    </template>

    <template #form>
      <!-- office_id -->
      <form-input
        label="Sucursal"
        name="office_id"
        v-model="form.office_id"
        type="select"
        :options="officeIdOptions"
        :form="form"
      />

      <!-- Week days -->
      <div class="col-span-6">
        <div class="text-gray-800 text-sm">
          Días de la semana:
        </div>
        <div class="mt-2 gap-2 flex flex-wrap justify-center">
          <div
            class=""
            v-for="day, index in days"
            :key="index"
          >
            <ui-checkbox
              :label="day"
              v-model="pickedDays[index]"
            />
          </div>
        </div>
        <jet-input-error
          :message="form.errors.days"
          class="mt-2"
        />
      </div>

      <!-- start_time -->
      <div class="col-span-6 sm:col-span-4">
        <div class="text-gray-800 text-sm">
          Inicio del Horario:
        </div>
        <time-input v-model="form.start_time" />

        <jet-input-error
          :message="form.errors.start_time"
          class="mt-2"
        />
      </div>

      <!-- end_time -->
      <div class="col-span-6 sm:col-span-4">
        <div class="text-gray-800 text-sm">
          Final del Horario:
        </div>
        <time-input v-model="form.end_time" />

        <jet-input-error
          :message="form.errors.end_time"
          class="mt-2"
        />
      </div>

    </template>

    <template #actions>
      <jet-action-message
        :on="form.recentlySuccessful"
        class="mr-3"
      >
        Guardado.
      </jet-action-message>

      <jet-button
        :class="{ 'opacity-25': form.processing }"
        :disabled="form.processing"
      >
        Guardar
      </jet-button>
    </template>
  </jet-form-section>
</template>

<script>
import JetButton from "@/Jetstream/Button.vue";
import JetFormSection from "@/Jetstream/FormSection.vue";
import JetActionMessage from "@/Jetstream/ActionMessage.vue";
import JetSecondaryButton from "@/Jetstream/SecondaryButton.vue";

import JetInputError from "@/Jetstream/InputError";

import UiCheckbox from "@/Shared/UI/Checkbox";

import FormInput from "@/Shared/Backend/Form/Input";
import TimeInput from "./TimeInput";

export default {
  props: ["doctor", "offices"],

  components: {
    JetActionMessage,
    JetButton,
    JetFormSection,
    JetSecondaryButton,
    JetInputError,

    FormInput,
    TimeInput,
    UiCheckbox,
  },

  setup() {
    const days = {
      1: "Lunes",
      2: "Martes",
      3: "Miercoles",
      4: "Jueves",
      5: "Viernes",
      6: "Sabado",
      7: "Domingo",
    };

    return {
      days,
    };
  },

  data() {
    return {
      pickedDays: {},
      form: this.$inertia.form({
        _method: "POST",
        days: [],
        start_time: null,
        end_time: null,
        office_id: null,
      }),
    };
  },

  computed: {
    officeIdOptions() {
      let list = {};
      this.offices.map((x) => {
        list[x.id] = x.name;
      });
      return list;
    },
  },

  methods: {
    onSubmitted() {
      const url = route("doctors.schedules.store", this.doctor.id);

      this.form.days = Object.keys(this.pickedDays).filter(
        (key) => this.pickedDays[key]
      );

      this.form.post(url, {
        // errorBag: "",
        preserveScroll: true,
        onSuccess: this.onSuccess,
      });
    },

    onSuccess() {
      // Clear inputs
      Object.keys(this.pickedDays).forEach(
        (key) => (this.pickedDays[key] = false)
      );
      this.form.days = [];
      this.form.start_time = null;
      this.form.end_time = null;
      this.form.clearErrors();
    },
  },
};
</script>
