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
      c("amount_paid", "Cantidad Pagadas"),
      c("getAppointmentsPaid", "Citas Restantes"),
      c("name", "Nombre"),
      c().type(cells.Buttons)
        .extend({
          buttons: [
            {
              label: "Marcar Asistencia",
              clicked({ row }) {
                if (
                  confirm("Estas seguro?")
                ) {
                  const url = route("patients.rates.assited", row.id);
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
