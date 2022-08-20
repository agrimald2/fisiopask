import dates from "@/ui/dates.js";

import { Inertia } from "@inertiajs/inertia";
import { cells, c } from "@ferchoposting/gridie";

export default (props, { attrs }) => {
  const queryString = window.location.search;

  const urlParms = new URLSearchParams(queryString);

  let currentPage = urlParms.get('page');

  if(!(currentPage >= 1)) currentPage = 1;

  const rows = props.model;

  const cols = [
    c("", "#")
    .extend({
      html: true,
    })
    .format(function (row) {
      let className = "bg-green-400";

      if (row.status == 4) className = "bg-red-400";

      //return `${row.id}`
      return `<p>${((currentPage - 1) * 120) + (props.model.indexOf(row) + 1)}<p>`;

    }),

    c("", "Estado")
      .extend({
        html: true,
      })
      .format(function (row) {
        let status = row.status;
        let className = "bg-status-" + status;

        let statusStr = "undefined";

        if(status == 1) statusStr = "CONFI";
        else if(status == 2) statusStr = "N A";
        else if(status == 3) statusStr = "ASIS";
        else if(status == 4) statusStr = "CAN";

        return `<span class="${className} text-white px-2 rounded">${statusStr}</span>`;
  
      }),

    c("date", "Fecha")
      .class("capitalize")
      .format((v) => dates.dateForApp(v)),

    c("", "Paciente")
      .extend({
        html: true,
      })
      .format(function (row) {
        console.log(row);
        return `${row.name} ${row.lastname1} ${row.lastname2}`;
      }),

    c("", "Licenciado")
      .extend({
        html: true,
      })
      .format(function (row) {
        let doc = props.doctors.find(element => element.id == row.doctor_id);
        return `${doc.name}`;
      }),

    c("office", "Sucursal"),

    c("", "Horario")
      .extend({ html: true })
      .format((value, { row }) => {
        return `${row.start} <i class="fas fa-angle-right center-text text-center"></i> ${row.end}`;
      }),

    c()
      .type(cells.Buttons)
      .extend({
        buttons: [
          {
            label: "Multi",
            clicked({ row }) {
              Inertia.visit(route("multipleBooking.pickDay", row.phone));
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

    c()
      .type(cells.Buttons)
      .extend({
        buttons: [
          {
            label: "Whatsapp",
            clicked({ row }) {
              const url = route('doctors.wame', row.id);
              Inertia.visit(url);
            }
          },
        ],
      }),

  ];
  function windowAppointment() {
    window.open("https://fisiosalud.pe/cita", "_blank", "toolbar=yes,scrollbars=yes,resizable=yes,top=250,left=500,width=600,height=600");
  }
  return { rows, cols };
};
