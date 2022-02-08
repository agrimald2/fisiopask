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
        />

        <form-input
            label="Resultado"
            name="result"
            v-model="form.result"
            type="select"
            :options="resultOptions"
            :form="form"
        />

        <form-input
          label="Fecha del Test"
          name="test_date"
          v-model="form.taken_at"
          type="date"
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
import JetDangerButton from "@/Jetstream/DangerButton.vue";
import JetFormSection from "@/Jetstream/FormSection.vue";
import JetActionMessage from "@/Jetstream/ActionMessage.vue";
import JetSecondaryButton from "@/Jetstream/SecondaryButton.vue";

import FormInput from "@/Shared/Backend/Form/Input";

export default {
  props: ["model", "doctorsMap", "companiesMap", "testTypesMap", "resultsArray"],

  components: {
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

        doctor_id: null,
        company_id: null,
        test_type_id: null,
        result: null,
        taken_at: null,
        observations: null,

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

        let list = {};

        var newArray = this.resultsArray.filter((x) => {
            return x.test_type_id == this.form.test_type_id;
        });

        let id = 0;
        newArray.map((x) => {
            list[id] = x.result;
            id++;
        });

        return list;
      },
  },
  
  methods: {
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