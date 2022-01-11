<template>
  <gridie
    class="w-full text-left"
    :cols="cols"
    :rows="patientRates"
  ></gridie>
</template>


<script>
import { Gridie, c, cells } from "@ferchoposting/gridie";
import { Inertia } from "@inertiajs/inertia";

export default {
  props: ["patientRates"],

  components: {
    Gridie,
  },

  mounted() {
    console.log(this.patientRates);
  },

  setup() {
    const cols = [
      c("name", "Nombre"),
      c("price", "Precio"),
      c("amount_paid", "Cantidad Pagada"),
      c("rate.stock", "Citas Totales"),
      c("appointments_paid", "Citas Pagadas"),
      c("appointments_assisted", "Citas Asistidas"),
      c("can_assist", "Puede Asistir"),
      c().type(cells.Buttons)
        .extend({
          buttons: [
            {
              label: "Marcar Asistencia",
              clicked({ row }) {
                if(row.can_assist_bool)
                {
                  if(confirm("Estas seguro?"))
                  {
                    const url = route("patients.rates.assited", row.id);
                    Inertia.get(url);
                  }
                }
                else
                {
                  confirm("Tiene que pagar para asistir");
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
