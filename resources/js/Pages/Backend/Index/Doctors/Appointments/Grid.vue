<template>
  <gridie
    class="w-full overflow-x-auto"
    :rows="rows"
    :cols="cols"
    style="text-align:center"
  />
</template>


<script>
import { cells, Gridie, c } from "@ferchoposting/gridie";
import { Inertia } from "@inertiajs/inertia";
import dates from "@/ui/dates.js";

export default {
  props: ["rows"],

  components: {
    Gridie,
  },

  setup() {
    const cols = [
      c("status_label", "Estado"),

      c("patient.name", "Paciente").format((value, { row }) => {
        return `${row.patient.name} ${row.patient.lastname1} ${row.patient.lastname2}`;
      }),

      c("office", "Sucursal"),

      c("", "Horario")
        .extend({ html: true })
        .format((value, { row }) => {
          return `${row.start} <i class="fas fa-angle-right"></i> ${row.end}`;
        }),

      c("date", "Fecha")
        .class("capitalize")
        .format((v) => dates.dateForHumans(v)),

      c()
        .type(cells.Buttons)
        .extend({
          buttons: [
            {
              label: "Detalles",
              clicked({ row }) {
                Inertia.visit(route("doctors.appointments.show", row.id));
              },
            },
          ],
        }),
    ];
    return { cols };
  },
};
</script>
