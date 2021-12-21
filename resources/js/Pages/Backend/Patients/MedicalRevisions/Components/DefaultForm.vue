<template>
  <JetFormSection @submitted="onSubmitted">
    <template #title>
      Crear una Historia Médica
    </template>

    <template #form>
      <!-- Description -->
      <FormInput
        label="Descripción"
        name="description"
        v-model="form.description"
        type="text"
        :form="form"
      />

      <!-- Pain Scale -->
      <FormInput
        label="Nivel de Dolor"
        name="pain_scale"
        v-model="form.pain_scale"
        type="number"
        :form="form"
      />

      <!-- Force Scale -->
      <FormInput
        label="Nivel de Fuerza"
        name="force_scale"
        v-model="form.force_scale"
        type="number"
        :form="form"
      />

      <!-- Joint Range -->
      <FormInput
        label="Rango Articular"
        name="joint_range"
        v-model="form.joint_range"
        type="number"
        :form="form"
      />

      <!-- Recovery Progress -->
      <FormInput
        label="Avance de Recuperación"
        name="recovery_progress"
        v-model="form.recovery_progress"
        type="number"
        :form="form"
      />

      <!-- Treatments -->
      <FormInput
        label="Tratamiento"
        name="treatments"
        v-model="form.treatment_id"
        type="select"
        :options="treatments"
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
  props: ["history_group", "treatments"],

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

        description: null,

        pain_scale: null,
        force_scale: null,
        joint_range: null,
        recovery_progress: null,

        treatment_id: null,

        history_group_id: null,
      }),
    };
  },

  methods: {
    onSubmitted() {
      this.form.patient_id = this.history_group.patient_id;
      this.form.doctor_id = this.history_group.doctor_id;
      this.form.history_group_id = this.history_group.id;

      const url = route("medicalrevision.store");

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
