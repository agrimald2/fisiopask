<template>
  <JetFormSection @submitted="onSubmitted">
    <template #title>
      {{ model ? "Editar" : "Crear" }} una Transacción
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

      <FormInput
        label="Monto S/."
        name="amount"
        v-model="form.quantity"
        type="number"
        :form="form"
      />

      <div class="col-span-6 sm:col-span-4">
        <jet-label for="billssubfamily_id" value="Subfamilia" />
        <Multiselect
          id="billsSubfamily"
          label="Subfamilia"
          name="billssubfamily_id"
          v-model="form.billssubfamily_id"
          :close-on-select="true"
          :searchable="true"
          :create-option="false"
          :options="subfamilies"
          :form="form"
        />
      </div>

      <div class="col-span-6 sm:col-span-4">
        <jet-label for="receiver" value="Receptor" />
        <Multiselect
          id="receiver"
          label="receiver"
          name="receiver"
          v-model="form.receiver"
          :options="receivers"
          :close-on-select="true"
          :searchable="true"
          :create-option="false"
          :form="form"
        />
      </div>

      <div class="col-span-6 sm:col-span-4">
        <jet-label for="paymentway" value="Forma de pago" />
        <Multiselect
          id="paymentway"
          label="paymentway"
          name="paymentway"
          v-model="form.paymentway"
          :options="paymentMethods"
          :close-on-select="true"
          :searchable="true"
          :create-option="false"
          :form="form"
        />
      </div>

      <div class="col-span-6 sm:col-span-4">
        <jet-label for="paymentway" value="Origen del Dinero" />
        <Multiselect
          id="moneyOrigin"
          label="moneyOrigin"
          name="moneyOrigin"
          v-model="form.moneyOrigin"
          :options="origins"
          :close-on-select="true"
          :searchable="true"
          :create-option="false"
          :form="form"
        />
      </div>

      <div class="col-span-6 sm:col-span-4">
        <jet-label for="paymentway" value="Pagador" />
        <Multiselect
          id="payer"
          label="payer"
          name="payer"
          v-model="form.payer"
          :options="payers"
          :close-on-select="true"
          :searchable="true"
          :create-option="false"
          :form="form"
        />
      </div>
    </template>

    <template #actions>
      <JetActionMessage :on="form.recentlySuccessful" class="mr-3">
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
import Multiselect from "@vueform/multiselect";
import JetLabel from "@/Jetstream/Label";

import FormInput from "@/Shared/Backend/Form/Input";

export default {
  props: [
    "model",
    "subfamilies",
    "origins",
    "payers",
    "paymentMethods",
    "receivers",
  ],

  components: {
    JetActionMessage,
    JetButton,
    JetFormSection,
    JetSecondaryButton,
    Multiselect,
    FormInput,
    JetLabel,
  },

  data() {
    return {
      form: this.$inertia.form({
        _method: "POST",
        description: null,
        billssubfamily_id: null,
        receiver: null,
        paymentway: null,
        moneyOrigin: null,
        payer: null,
        quantity: null,

        ...this.model,
      }),
    };
  },

  methods: {
    onSubmitted() {
      if (this.form.description == null) this.form.description = "";
      if (this.model) {
        this.submitEditForm();
      } else {
        this.submitCreateForm();
      }
    },

    submitEditForm() {
      const url = route("bills.update", this.model.id);

      this.form._method = "PUT";

      this.form.post(url, {
        // errorBag: "",
        preserveScroll: true,
        onSuccess: this.onSuccess,
      });
    },

    submitCreateForm() {
      const url = route("bills.store");

      this.form._method = "POST";

      this.form.post(url, {
        // errorBag: "",
        preserveScroll: true,
        onSuccess: this.onSuccess,
      });
    },

    onSuccess() {
      // Clear inputs
    },
  },
};
</script>
