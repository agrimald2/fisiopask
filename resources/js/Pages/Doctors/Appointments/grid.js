import dates from "@/ui/dates.js";

import { Inertia } from "@inertiajs/inertia";
import { cells, c } from "@ferchoposting/gridie";

export default (props, { attrs }) => {
  const rows = props.model;

  const cols = [
    c("", "Estado")
      .extend({
        html: true,
      })
      .format(function (row) {
        let className = "bg-green-400";

        if (row.status == 4) className = "bg-red-400";

        return `<span class="${className} text-white px-2 rounded">${row.status_label}</span>`;
      }),

    c("date", "Fecha")
      .class("capitalize")
      .format((v) => dates.dateForHumans(v)),

    c("", "Paciente")
     .extend({ html: true})
     .format((value, {row}) => {
       return `${row.patient.fullname.toUpperCase()} <b style="color:green">${row.patient.is_new}</b>`;
     }),

    c("office", "Sucursal"),

    c("", "Horario")
      .extend({ html: true })
      .format((value, { row }) => {
        return `${row.start} <i class="fas fa-angle-right"></i> ${row.end}`;
      }),

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

  return { rows, cols };
};
