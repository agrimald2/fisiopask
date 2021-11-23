import { Inertia } from "@inertiajs/inertia";
import { cells, c } from "@ferchoposting/gridie";
import moment from "moment";

export default (props, { attrs }) => {
  const rows = props.model;

  const cols = [
    c("payment_method", "Nombre"),

    c("active", "Activo?").format((value, { row }) =>
      value == 1 ? "Si" : "No"
    ),

    c("created_at", "Creado").format((value, { row }) =>
      moment(value).format("YYYY MM DD h:mm A")
    ),

    {
      type: cells.Buttons,
      buttons: [
        {
          label: "Editar",
          clicked({ row }) {
            const url = route("paymentMethods.edit", row.id);
            Inertia.visit(url);
          },
        },
      ],
    },
  ];

  return { rows, cols };
};
