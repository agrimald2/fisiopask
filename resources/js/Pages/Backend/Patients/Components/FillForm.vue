<template>
  <div>
    <jet-form-section @submitted="onSubmitted">

      <template #form>
        <!-- District -->
        <form-input
          label="Distrito"
          name="district"
          v-model="form.district"
          type="text"
          :form="form"
        />

        <!-- Cubiculo  -->
        <form-input
          label="Recomendación"
          name="recommendation_id"
          v-model="form.recommendation_id"
          type="select"
          :options="recommendations"
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
  props: ["patientId", "appointmentId", "showDistrict", "showRecommendation", "recommendations"],

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
      form: this.$inertia.form({
        _method: "POST",

        district: null,
        recommendation_id: null,
      }),
    };
  },

  methods: {
    onSubmitted() {
      const url = route("patients.fillData", 
        [this.patientId, this.appointmentId]);

      this.form.post(url, {
        preserveScroll: true,
        onSuccess: this.onSuccess,
      });
    },
  },
};
</script>
