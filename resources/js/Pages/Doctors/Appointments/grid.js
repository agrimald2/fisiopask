import dates from "@/ui/dates.js";

import { Inertia } from "@inertiajs/inertia";
import { cells, c } from "@ferchoposting/gridie";
import { parserOptions } from "@vue/compiler-dom";
import { bindKey } from "lodash";

export default (props, { attrs }) => {
  const queryString = window.location.search;

  const urlParms = new URLSearchParams(queryString);

  const currentPage = urlParms.get('page');

  const rows = props.model;

  const cols = [
    c("", "#")
    .extend({
      html: true,
    })
    .format(function (row) {
      let className = "bg-green-400";

      if (row.status == 4) className = "bg-red-400";

      return `<p>${((currentPage - 1) * 15) + (props.model.indexOf(row) + 1)}<p>`;

    }),

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

    c("patient.name", "Paciente"),

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
            label: "Agendar Multiples Citas",
            clicked({ row }) {
              Inertia.visit(route("multipleBooking.pickDay", row.patient.id));
            },
          },
        ],
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
  function windowAppointment() {
    window.open("https://fisiosalud.pe/cita", "_blank", "toolbar=yes,scrollbars=yes,resizable=yes,top=250,left=500,width=600,height=600");
  }
  return { rows, cols };
};
