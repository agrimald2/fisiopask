<template>
  <div>
    <jet-form-section @submitted="onSubmitted">
      <template #title>
        {{ model ? "Editar " : "Crear un nuevo" }} Test
      </template>

      <template #form>
        <form-input
            label="Tipo de Test"
            name="testType"
            v-model="form.test_type_id"
            type="select"
            :options="testTypesMap"
            :form="form"
            @input="check()"
        />
        <div v-show="form.test_type_id != null" class="col-span-6 sm:col-span-4">
          <template v-if="form.testResultType == 0">
            <template v-for="item in form.resultCount" :key="item">
              <jet-label
                :for="'result' + item"
                :value="'Resultado ' + item"
              />
              <input-select
                :id="'result'+item"
                type="select"
                :options="resultOptions"
                class="mt-1 block w-full"
                v-model="form.results[item]"
              />
            </template>
          </template>
          <template v-else>
            <template v-for="item in form.resultCount" :key="item">
              <jet-label
                :for="'result' + item"
                :value="'Resultado ' + item"
              />
              <jet-input
                :label="'Resultado ' + item"
                :name="'result' + item"
                v-model="form.results[item]"
                type="text"
                class="mt-1 block w-full"
                :form="form"
              />
            </template>
          </template>
        </div>

        <form-input
          label="Fecha del Test"
          name="test_date"
          v-model="form.taken_at"
          type="datetime-local"
          :form="form"
        />

        <form-input
          label="Fecha del Resultado"
          name="result_date"
          v-model="form.result_at"
          type="datetime-local"
          :form="form"
        />

        <form-input
            label="Doctor"
            name="doctor"
            v-model="form.doctor_id"
            type="select"
            :options="doctorsMap"
            :form="form"
        />
          
        <form-input
            label="Compañía"
            name="company"
            v-model="form.company_id"
            type="select"
            :options="companiesMap"
            :form="form"
        />

        <form-input
            label="Observaciones"
            name="observations"
            v-model="form.observations"
            type="text"
            :form="form"
        />
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
  </div>
</template>

<script>
import JetButton from "@/Jetstream/Button.vue";
import JetInput from "@/Jetstream/Input";
import JetLabel from "@/Jetstream/Label";
import JetDangerButton from "@/Jetstream/DangerButton.vue";
import JetFormSection from "@/Jetstream/FormSection.vue";
import JetActionMessage from "@/Jetstream/ActionMessage.vue";
import JetSecondaryButton from "@/Jetstream/SecondaryButton.vue";

import InputSelect from "@/Shared/Backend/Form/Components/SelectElement.vue";

import FormInput from "@/Shared/Backend/Form/Input";

export default {
  props: ["model", "doctorsMap", "companiesMap", "testTypesMap", "testTypes", "resultsArray", "patient_id"],

  components: {
    InputSelect,
    JetInput,
    JetLabel,
    JetActionMessage,
    JetButton,
    JetFormSection,
    JetSecondaryButton,

    JetDangerButton,

    FormInput,
  },

  data() {
    return {
      previousTestType: null,
      form: this.$inertia.form({
        _method: "POST",

        testResultType: null,
        resultCount: 1,
        doctor_id: null,
        company_id: null,
        test_type_id: null,
        results: {},
        taken_at: null,
        result_at: null,
        observations: null,
        patient_id: this.patient_id,

        ...this.model,
      }),
    };
  },

  mounted() {
      if(this.model)
      {
        const list = this.resultOptions;
        for(const key in list)
        {
            if(list[key] == this.model.result) this.form.result = key;
        }
      }
  },

  computed: {
      resultOptions() {
        if(this.previousTestType != this.form.test_type_id)
        {
            this.form.result = null;
            this.previousTestType = this.form.test_type_id;
        }

        const model = this.testTypes.filter((x) => {
          return x.id == this.form.test_type_id;
        });

        this.form.testResultType = model[0].type;
        this.form.resultCount = model[0].result_count;

        let list = {};

        var newArray = this.resultsArray.filter((x) => {
            return x.test_type_id == this.form.test_type_id;
        });

        let id = 1;
        list[0] = "Pendiente";
        newArray.map((x) => {
            list[id] = x.result;
            id++;
        });

        return list;
      },
  },
  
  methods: {
    check() {
        const model = this.testTypes.filter((x) => {
          return x.id == this.form.test_type_id;
        });

        this.form.testResultType = model[0].type;
        this.form.resultCount = model[0].result_count;
    },
    onSubmitted() {
      const model = this.model;
      let url = route("tests.store");

      if (model) {
        url = route("tests.update", model.id);
        this.form._method = "PUT";
      }

      this.form.post(url, {
        preserveScroll: true,
        onSuccess: this.onSuccess,
      });
    },
  },
};
</script>

<style scoped>
  .cb{
    width:5vw; height:5vw
  }
</style>