<template>
  <div class="py-6">
    <div class="px-2">
      <div class="text-xl">
        Añadir un producto a
        <span class="font-bold">
          {{ patient.name }}
          {{ patient.lastname1 }}
          {{ patient.lastname2 }}
        </span>
      </div>

      <form @submit.prevent="addRate">
        <ui-product-select v-model="input.product" />
        <div v-if="input.product != null">
          <div v-if="input.product.is_product == true" class="mt-8 grid">
            <div class="font-bold">Cantidad</div>
            <input
              type="text"
              v-model="input.qty"
            >
          </div>
        </div>

        <div v-show="input.product">
          <div class="mt-8 grid">
            <ui-button
              color="green"
              @click="addRate"
            >Añadir al Carrito</ui-button>
          </div>

          <div
            class="text-center mt-4 text-lg"
            v-if="input.product"
          >
            {{ input.product.name }} x {{ input.qty }} = ${{ input.product.price * input.qty }}
          </div>
        </div>
      </form>

    </div>

    <div class="border-t my-12"></div>

    <div class="px-2">
      <div class="text-2xl">
        Carrito
      </div>

      <table class="mt-4 w-full text-left">
        <tr>
          <th>Nombre</th>
          <th>Precio</th>
          <th>Cantidad</th>
          <th>Total</th>
        </tr>

        <tr
          v-for="cartRate, id in cart"
          :key="id"
        >
          <td class="py-2">
            {{ cartRate.name }}
            <div
              class="text-sm text-red-400 underline cursor-pointer"
              @click="removeRate(cartRate)"
            >Quitar del carrito</div>
          </td>
          <td class="py-2">${{ cartRate.price }}</td>
          <td class="py-2">x{{ cartRate.qty }}</td>
          <td class="py-2">${{ cartRate.price * cartRate.qty }}</td>
        </tr>

        <tr
          class="border-t"
          v-show="cart.length > 0"
        >
          <td colspan="3">Total</td>
          <td class="py-2 text-xl">${{ cartTotal }}</td>
        </tr>
      </table>
    </div>

    <div v-show="cart.length > 0">
      <div class="mt-4 grid px-4">
        <ui-button
          color="green"
          @click="submit"
        >Añadir a la Cuenta del Cliente
            {{appointment_id}}
        </ui-button>
      </div>
    </div>

  </div>
</template>


<script>
import UiProductSelect from "@/Pages/Backend/Rates/Components/ProductSelect.vue";
import UiButton from "@/Shared/Frontend/Button";

export default {
  props: {
    patient: null,
    submitUrl: null,
    options: {
      type: Object,
      default: {},
    },
    appointment_id: null
  },

  components: {
    UiProductSelect,
    UiButton,
  },

  computed: {
    cartTotal() {
      return this.cart.map((x) => x.price * x.qty).reduce((a, b) => a + b, 0);
    },
  },

  methods: {
    addRate() {
      if(!this.input.product.is_product) this.input.qty = 1;
      if (this.input.product && !isNaN(this.input.qty) && this.input.qty > 0) {
        const cartRate = {
          id: this.input.product.id,
          name: this.input.product.name,
          price: this.input.product.price,
          qty: Math.ceil(this.input.qty),
        };

        this.input.qty = null;

        this.cart.push(cartRate);
      }
    },

    submit() {
      const url = this.submitUrl;
      const data = {
        cart: this.cart,
        appointment_id: this.appointment_id,
        ...this.options,
      };
      this.$inertia.post(url, data);
      this.cart = [];

    },

    removeRate(y) {
      const index = this.cart.findIndex((x) => x == y);

      if (index > -1) {
        this.cart.splice(index, 1);
      }
    },
  },

  data() {
    return {
      input: {
        qty: 1,
        product: null,
      },
      cart: [],
    };
  },
};
</script>
