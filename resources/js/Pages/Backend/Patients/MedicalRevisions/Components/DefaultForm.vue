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
        label="Nivel de Dolor (0 a 10)"
        name="pain_scale"
        v-model="form.pain_scale"
        type="number"
        min="0"
        max="10"
        value="0"
        step="1"
        onkeypress="return (event.charCode >= 48 && event.charCode <= 57)"
        onkeyup="if(this.value>10){this.value='10';}else if(this.value<0){this.value='0';}"
        :form="form"
      />

      <!-- Force Scale -->
      <FormInput
        label="Nivel de Fuerza (-5 a 5)"
        name="force_scale"
        v-model="form.force_scale"
        type="text"
        min="-5"
        max="5"
        step="1"
        onkeyup="if(this.value>5){this.value='5';}else if(this.value<-5){this.value='-5';}"
        :form="form"
      />

      <!-- Joint Range -->
      <FormInput
        label="Rango Articular (0° a 180°) (5*) en Grados ° "
        name="joint_range"
        v-model="form.joint_range"
        type="number"
        min="0"
        max="180"
        step="5"
        onkeyup="if(this.value>180){this.value='180';}else if(this.value<0){this.value='0';}"
        :form="form"
      />

      <!-- Recovery Progress -->
      <FormInput
        label="Avance de Recuperación % (5*)"
        name="recovery_progress"
        v-model="form.recovery_progress"
        type="number"
        min="0"
        max="100"
        step="5"
        onkeyup="if(this.value>100){this.value='100';}else if(this.value<0){this.value='0';}"
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

      <!-- Treatments -->
      <FormInput
        label="Tratamiento"
        name="treatments1"
        v-model="form.t1"
        type="select"
        :options="treatments"
        :form="form"
      />

      <!-- Treatments -->
      <FormInput
        label="Tratamiento"
        name="treatments2"
        v-model="form.t2"
        type="select"
        :options="treatments"
        :form="form"
      />

      <!-- Treatments -->
      <FormInput
        label="Tratamiento"
        name="treatments3"
        v-model="form.t3"
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

        t1: null,
        t2: null,
        t3: null,

        history_group_id: null,

        treatments: [],
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
