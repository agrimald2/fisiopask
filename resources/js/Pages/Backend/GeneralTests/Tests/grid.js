import { Inertia } from "@inertiajs/inertia";
import { cells, c } from "@ferchoposting/gridie";
import moment from "moment";

export default (props, { attrs }) => {
  const rows = props.model;

  const cols = [
    c("test_type.name", "Tipo de Test"),
    c("patient.fullname", "Paciente"),
    c("doctor.fullname", "Doctor"),
    c("company.name", "Compañía"),

    c("result", "Resultado"),
    c("observations", "Observaciones"),

    c("taken_at", "Tomado el").format((value, { row }) =>
      moment(value).format("YYYY MM DD h:mm A")
    ),
    c("result_at", "Resultado el").format((value, { row }) =>
      moment(value).format("YYYY MM DD h:mm A")
    ),

    {
      type: cells.Buttons,
      buttons: [
        {
          label: "Editar",
          clicked({ row }) {
            const url = route("tests.edit", row.id);
            Inertia.visit(url);
          },
        },
      ],
    },

    {
      type: cells.Buttons,
      buttons: [
        {
          label: "PDF",
          clicked({ row }) {
            const url = route("tests.downloadPDF", row.id);
            Inertia.visit(url);
          },
        },
      ],
    },
  ];

  return { rows, cols };
};
