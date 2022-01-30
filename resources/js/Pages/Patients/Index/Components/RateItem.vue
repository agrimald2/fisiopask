<template>
  <div class="border rounded">
    <!-- Summary -->
    <div
      class="select-none p-2 cursor-pointer hover:bg-gray-50"
      @click="expanded = !expanded"
    >
      <div class="flex flex-wrap items-center justify-between">
        <div>
          <div class="mt-3 text-sm medium-text">
            {{ model.name }}
          </div>
        </div>
      </div>
      <div class="flex gap-4 items-center">
        Precio: {{ model.price }}
        <br>
        Monto Pagado: {{ model.payed }}
      </div>

      <div v-if="model.payed < model.price">
        <div>
          <input
            type="number"
            min="1"
            :max="(model.price - model.payed) / model.appointment_price"
            onkeypress="return (event.charCode >= 48 && event.charCode <= 57)"
            :onkeyup="`if(this.value>${(model.price - model.payed) / model.appointment_price}){this.value='${(model.price - model.payed) / model.appointment_price}';}else if(this.value<1){this.value='1';}`"
            v-model="amount"
          >
        </div>
        <ui-button
        color="green"
        @click="pay"
        >Pagar Tarifa</ui-button>
      </div>
    </div>
  </div>
</template>

<script>
import UiButton from "@/Shared/Frontend/Button";

export default {
  props: ["model", "appointmentsLeft"],

  components: {
    UiButton,
  },

  data() {
    return { 
        amount: 2,
        expanded: false,
        can_assist: true
    };
  },

  methods: {
    pay() {
      const id = this.model.id;
      const url = route("area.patients.pay", id);
      this.$inertia.post(url, { amount: this.amount, rate_id: this.model.id });
    }
  },
};
</script>
