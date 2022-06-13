<template>
  <JetFormSection @submitted="onSubmitted">
    <template #title>
      Crear una Historia Médica
    </template>

    <template #form>
      <template v-for="attribute in attributes" :key="attribute.id">
        <div v-if="attribute.input_type == 0" class="col-span-6 sm:col-span-4">
          <jet-label
            :for="attribute.input_name"
            :value="attribute.input_name"
          />
          <jet-input
            :id="attribute.input_name"
            type="text"
            class="mt-1 block w-full"
            v-model="form.attributes[attribute.input_name]"
            @input="updateValue($event, attribute.input_name, $event.target.value)"
          />
        </div>
        <!--form-input
          v-if="attribute.input_type == 0"
          :label="attribute.input_name"
          :name="attribute.input_name"
          v-model="form.attributes[attribute.input_name]"
          type="text"
          :form="form"
        /-->
        <!--form-input
          v-else-if="attribute.input_type == 1"
          :label="attribute.input_name"
          :name="attribute.input_name"
          v-model="form.attributes[attribute.input_name]"
          type="numeric"
          :form="form"
        />
        <form-input
          v-else-if="attribute.input_type == 2"
          :label="attribute.input_name"
          :name="attribute.input_name"
          v-model="form.attributes[attribute.input_name]"
          type="select"
          :options="options(attribute.related_model)"
          :form="form"
        />
        <form-input
          v-else-if="attribute.input_type == 3"
          :label="attribute.input_name"
          :name="attribute.input_name"
          v-model="form.attributes[attribute.input_name]"
          type="multselect"
          :options="options(attribute.related_model)"
          :form="form"
        />
        <form-input
          v-else-if="attribute.input_type == 4"
          :label="attribute.input_name"
          :name="attribute.input_name"
          v-model="form.attributes[attribute.input_name]"
          type="checkbox"
          :form="form"
        /-->
      </template>
    </template>

    <template #actions>
      <JetActionMessage
        :on="form.recentlySuccessful"
        class="mr-3"
      >
        Guardado.
      </JetActionMessage>

      <JetButton
        :class="{ 'opacity-25': form.processing }"
        :disabled="form.processing"
      >
        Guardar
      </JetButton>
    </template>

    
  </JetFormSection>
</template>

<script>
import Multiselect from '@vueform/multiselect'
import JetLabel from "@/Jetstream/Label";

import JetInput from "@/Jetstream/Input";
import JetButton from "@/Jetstream/Button.vue";
import JetFormSection from "@/Jetstream/FormSection.vue";
import JetActionMessage from "@/Jetstream/ActionMessage.vue";
import JetSecondaryButton from "@/Jetstream/SecondaryButton.vue";

import FormInput from "@/Shared/Backend/Form/Input";

export default {
  props: ["history_group", "diagnostics", "treatments", "analysis", "affected_areas", "checkedNames", "attributes"],

  components: {
    JetLabel,
    JetInput,
    JetActionMessage,
    JetButton,
    JetFormSection,
    JetSecondaryButton,

    FormInput,
    Multiselect,
  },

  data() {
    return {
      //map inside form with names of attributes
      attrs: null,
      form: this.$inertia.form({
        _method: "POST",

        patient_id: null,
        doctor_id: null,
        history_group_id: null,

        attributes: {},
        values: {},
      }),
    };
  },

  methods: {
    onSubmitted() {
      this.form.patient_id = this.history_group.patient_id;
      this.form.doctor_id = this.history_group.doctor_id;
      this.form.history_group_id = this.history_group.id;

      const url = route("medicalhistory.store");

      this.form._method = "POST";

      this.form.post(url, {
        // errorBag: "",
        preserveScroll: true,
        onSuccess: this.onSuccess,
      });
    },
    updateValue(e, i, v) {
      console.log(v);
      this.form.attributes[i] = v;
      console.log(this.form.attributes[i]);
      //let x = this.form.values[i];
      //this.form.values[i] = v;
    },
    options(id) {
      if(id == 1) return this.affected_areas;
      else if(id == 2) return this.diagnostics;
      else if(id == 3) return this.treatments;
      
      return null;
    },
  },
};
</script>
<style src="@vueform/multiselect/themes/default.css"></style>
