<template>
  <gridie
    class="w-full text-left"
    :cols="cols"
    :rows="payments"
  ></gridie>
</template>


<script>
import { Gridie, c, cells } from "@ferchoposting/gridie";
import { Inertia } from "@inertiajs/inertia";

export default {
  props: ["payments"],

  components: {
    Gridie,
  },

  setup() {
    const cols = [
      c("created_at", "Fecha"),
      c("payment_method", "Método de Pago"),
      c("ammount", "Monto"),
      c("concept", "Concepto"),
      c("patient.fullname", "Cliente"),
      c().type(cells.Buttons)
        .extend({
          buttons: [
            {
              label: "Anular",
              clicked({ row }) {
                if(confirm("Estás seguro?"))
                {
                  const url = route("patients.rates.payments.cancel", row.id);
                  Inertia.get(url);
                }
              },
            },
          ],
        }),
    ];

    return { cols };
  },
};
</script>
