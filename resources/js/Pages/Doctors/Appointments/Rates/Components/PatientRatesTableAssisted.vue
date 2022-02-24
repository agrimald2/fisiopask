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

  setup() {
    const cols = [
      c("name", "Nombre"),
      c("price", "Precio"),
      c("payed", "Cantidad Pagada"),
      c("sessions_total", "Citas Totales"),
      c("appointments_paid", "Citas Pagadas"),
      c("appointments_assisted", "Citas Asistidas"),
      c("can_assist_string", "Puede Asistir"),
      c().type(cells.Buttons)
        .extend({
          buttons: [
            {
              label: "Marcar Asistencia",
              clicked({ row }) {
                if(row.can_assist)
                {
                  if(confirm("Estás seguro?"))
                  {
                    const url = route("patients.rates.assisted", row.id);
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
