<template>
  <JetFormSection @submitted="onSubmitted">
    <template #title>
      Crear una Historia Médica
    </template>

    <template #form>
      <!-- Antecedentes -->
      <FormInput
        label="Antecedentes"
        name="background"
        v-model="form.background"
        type="text"
        :form="form"
      />

      <!-- Advertencias -->
      <FormInput
        label="Advertencias"
        name="warnings"
        v-model="form.warnings"
        type="text"
        :form="form"
      />

      <!-- Description -->
      <FormInput
        label="Descripción"
        name="description"
        v-model="form.description"
        type="text"
        :form="form"
      />

      <!-- Diagnostic -->
      <FormInput
        label="Diagnósticos"
        name="diagnostics"
        v-model="form.diagnostic_id"
        type="select"
        :options="diagnostics"
        :form="form"
      />

      <!-- Treatments -->
      <FormInput
        label="Tratamientos"
        name="treatments"
        v-model="form.treatment_id"
        type="select"
        :options="treatments"
        :form="form"
      />

      <!-- Analysis -->
      <FormInput
        label="Análisis"
        name="analysis"
        v-model="form.analysis_id"
        type="select"
        :options="analysis"
        :form="form"
      />

      <!-- Afected Areas -->
      <FormInput
        label="Áreas Afectadas"
        name="affected_areas"
        v-model="form.affected_area_id"
        type="select"
        :options="affected_areas"
        :form="form"
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
import JetButton from "@/Jetstream/Button.vue";
import JetFormSection from "@/Jetstream/FormSection.vue";
import JetActionMessage from "@/Jetstream/ActionMessage.vue";
import JetSecondaryButton from "@/Jetstream/SecondaryButton.vue";

import FormInput from "@/Shared/Backend/Form/Input";

export default {
  props: ["history_group", "diagnostics", "treatments", "analysis", "affected_areas"],

  components: {
    JetActionMessage,
    JetButton,
    JetFormSection,
    JetSecondaryButton,

    FormInput,
  },

  data() {
    return {
      form: this.$inertia.form({
        _method: "POST",

        patient_id: null,
        doctor_id: null,

        background: null,
        warnings: null,
        description: null,

        pain_scale: 1,
        force_scale: 1,
        joint_range: 1,
        recovery_progress: 1,

        diagnostic_id: null,
        treatment_id: null,
        analysis_id: null,
        affected_area_id: null,

        history_group_id: null,
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
  },
};
</script>
