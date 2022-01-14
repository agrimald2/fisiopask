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
      c("", "Estado")
        .extend({
            html: true,
        })
        .format(function (row) {
            let colorClass = (row.state == 0 ? "bg-green-400" : "bg-red-400");

            return `<span class="${colorClass} text-white px-2 rounded">${row.status_label}</span>`;
        }),
      c().type(cells.Buttons)
        .extend({
          buttons: [
            {
              label: "Detalles",
              clicked({ row }) {
                const url = route("patients.rates.payments", row.id);
                Inertia.get(url);
              },
            },
          ],
        }),
    ];

    return { cols };
  },
};
</script>
