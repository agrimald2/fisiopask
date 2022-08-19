import { Inertia } from "@inertiajs/inertia";
import { cells, c } from "@ferchoposting/gridie";
import moment from "moment";

export default (props, { attrs }) => {
  const rows = props.model;

  const cols = [
    c("dni", "DNI"),
    c("name", "Nombre").format(
      (value, { row }) =>
        `${value} ${row.lastname1} ${row.lastname2 ? row.lastname2 : ""}`
    ),

    c("", "Edad").format(
      (value, { row }) => moment().year() - moment(row.birth_date).year()
    ),
    c("sex", "Sexo"),
    c("phone", "Teléfono"),

    {
      type: cells.Buttons,
      attrs: {
        class: "grid gap-1",
      },
      buttons: [
        {
          label: "Editar",
          clicked({ row }) {
            const url = route("patients.edit", row.id);
            Inertia.visit(url);
          },
        },

        {
          label: "Historia Clínica",
          clicked({ row }) {
            const url = route("patients.historygroup.index", row.id);
            Inertia.visit(url);
          },
        },

        {
          label: "Servicios y Pagos",
          clicked({ row }) {
            const url = route("patients.rates.index", [row.id, 1]);
            Inertia.visit(url);
          },
        },

        {
          label: "Copiar Link de Acceso",
          clicked({ row }) {
            prompt("Dashboard link", row.link);
          },
        },

        {
          label: "Whatsapp",
          clicked({ row }) {
            const url = route('doctors.wame', row.phone);
            Inertia.visit(url);
          }
        }
      ],
    },
  ];

  return { rows, cols };
};
