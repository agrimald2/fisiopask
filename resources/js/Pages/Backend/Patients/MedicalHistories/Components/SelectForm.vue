<template>
  <JetFormSection @submitted="onSubmitted">
    <template #title>
      Crear una Historia Médica
    </template>

    <template #form>
      <!-- types -->
      <form-input
        label="Tipo"
        name="type"
        v-model="form.type_id" 
        :form="form"
        type="select"
        :options="options"
      />
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
  //import Multiselect from '@vueform/multiselect'
  import JetLabel from "@/Jetstream/Label";

import JetButton from "@/Jetstream/Button.vue";
import JetFormSection from "@/Jetstream/FormSection.vue";
import JetActionMessage from "@/Jetstream/ActionMessage.vue";
import JetSecondaryButton from "@/Jetstream/SecondaryButton.vue";

import FormInput from "@/Shared/Backend/Form/Input";
import { Inertia } from '@inertiajs/inertia';

export default {
  props: ["patientId", "doctorId", "id", "types"],

  components: {
    JetLabel,
    JetActionMessage,
    JetButton,
    JetFormSection,
    JetSecondaryButton,

    FormInput,
  },

  data() {
    return {
      form: this.$inertia.form({
        type_id: null,
        patient_id: this.patientId,
        doctor_id: this.doctorId,
      }),
    };
  },

  methods: {
    onSubmitted() {
      const url = route("patients.historygroup.store");

      this.form.post(url, {
        preserveScroll: true,
        onSuccess: this.onSuccess,
      });
    },
  },

  computed: {
    options() {
      let map = {};

      this.types.forEach(element => {
        map[element.id] = element.name;
      });
      return map;
    }
  }
};
</script>
<style src="@vueform/multiselect/themes/default.css"></style>
