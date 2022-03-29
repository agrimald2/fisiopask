<template>
  <div class="border rounded">
    <!-- Summary -->
    <div
      style="background-color:#80808040"  
      class="select-none p-2 cursor-pointer hover:bg-gray-50"
      @click="expanded = !expanded"
    >
      <div class="flex flex-wrap items-center justify-between"
        style="
        background-color:white;
        padding: 5px;
        justify-content: center;
        "
      >
        <div>
          <div class="medium-text" style="font-weight:bold">
            {{ model.name }}
          </div>
        </div>
      </div>
      <div class="flex gap-4 items-center" style="justify-content: center; text-align: center;">
        <div style="width:100%; text-align:left">
          <div style="font-size:1.05rem">
            Precio total
          </div>
          <div style="font-size:1.05rem">
            Monto pagado
          </div>
          <div style="font-size:1.05rem">
            Sesiones totales
          </div>
          <div style="font-size:1.05rem">
            Sesiones pagadas
          </div>
        </div>
        <div style="width:100%;">
          <div style="font-size:1.05rem">
            S/. {{model.price}}
          </div>
          <div style="font-size:1.05rem">
            S/. {{ model.payed }}
          </div>
          <div style="font-size:1.05rem">
            {{ model.price / model.appointment_price }}
          </div>
          <div style="font-size:1.05rem">
            {{ model.payed / model.appointment_price }}
          </div>
        </div>
      </div>

      <p style="
          text-align: center;
          font-weight: bold;
          color: cadetblue;
          font-size: 1.2rem;
          margin-top: 0.5rem;      
        ">
      ¿Cuántas sesiones deseas pagar?
      </p>
      <p style="text-align:center">
        Te quedan un total de <strong> {{(model.price / model.appointment_price)-(model.payed / model.appointment_price)}} </strong> citas por pagar
      </p>

      <div v-if="model.payed < model.price">
        <div style="justify-content:center; display:flex">
          <input
            style="text-align:center"
            type="number"
            min="1"
            onkeyup="if(this.amount>(model.price - model.payed) / model.appointment_price){amount=(model.price - model.payed) / model.appointment_price;}else if(this.amount<0){this.amount='0';}"
            :max="`${(model.price - model.payed) / model.appointment_price}`"
            v-model="amount"
          >
        </div>
        <p style="
        text-align: center;
        font-size: 1.3rem;        
        ">
          S/. {{amount * model.appointment_price}}
        </p>
        <div style="justify-content:center; display:flex;">
          <button
          style="
            color: white;
            background-color: cadetblue;
            margin-top: 1rem;
            height: 5vh;
            border: 1px solid white;
            width: 20vw;
            border-radius: 10%;
          "
          @click="pay"
          > 
          Pagar
          </button>
        </div>

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
        amount: 1,
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
