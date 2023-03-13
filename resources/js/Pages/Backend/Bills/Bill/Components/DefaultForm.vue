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

      <div class="col-span-6 sm:col-span-4">
        <jet-label for="isDoubleChecked" value="¿Doble Autorización?" />
        <div class="item">
          <div class="toggle-pill-color">
            <input
              type="checkbox"
              id="pill3"
              label="isDoubleChecked"
              name="isDoubleChecked"
              v-model="form.isDoubleChecked"
            />
            <label for="pill3"></label>
          </div>
        </div>
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
        isDoubleChecked: null,

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

      console.log("Hola");
      console.table(this.form);

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
<style scoped>
/* toggle-pill-color */
.toggle-pill-color input[type="checkbox"] {
  display: none;
}
.toggle-pill-color input[type="checkbox"] + label {
  display: block;
  position: relative;
  width: 3em;
  height: 1.6em;
  margin-bottom: 20px;
  border-radius: 1em;
  background: #e84d4d;
  box-shadow: inset 0px 0px 5px rgba(0, 0, 0, 0.3);
  cursor: pointer;
  -webkit-user-select: none;
  -moz-user-select: none;
  -ms-user-select: none;
  -webkit-transition: background 0.1s ease-in-out;
  transition: background 0.1s ease-in-out;
}
.toggle-pill-color input[type="checkbox"] + label:before {
  content: "";
  display: block;
  width: 1.2em;
  height: 1.2em;
  border-radius: 1em;
  background: #fff;
  box-shadow: 2px 0px 5px rgba(0, 0, 0, 0.2);
  position: absolute;
  left: 0.2em;
  top: 0.2em;
  -webkit-transition: all 0.2s ease-in-out;
  transition: all 0.2s ease-in-out;
}
.toggle-pill-color input[type="checkbox"]:checked + label {
  background: #47cf73;
}
.toggle-pill-color input[type="checkbox"]:checked + label:before {
  box-shadow: -2px 0px 5px rgba(0, 0, 0, 0.2);
  left: 1.6em;
}
/* toggle-pill-color end */
</style>
