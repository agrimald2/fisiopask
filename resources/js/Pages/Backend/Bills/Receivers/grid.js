import { Inertia } from "@inertiajs/inertia";
import { cells, c } from "@ferchoposting/gridie";
import moment from "moment";

export default (props, { attrs }) => {
  const rows = props.model;

  const cols = [
    c("name", "Nombre"),
    c("document", "Documento"),

    c("created_at", "Creado").format((value, { row }) =>
      moment(value).format("YYYY MM DD h:mm A")
    ),

    {
      type: cells.Buttons,
      buttons: [
        {
          label: "Editar",
          clicked({ row }) {
            const url = route("billsreceivers.edit", row.id);
            Inertia.visit(url);
          },
        },
      ],
    },
  ];

  return { rows, cols };
};
