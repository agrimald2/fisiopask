import { Inertia } from "@inertiajs/inertia";
import { cells, c } from "@ferchoposting/gridie";
import moment from "moment";

export default (props, { attrs }) => {
  const rows = props.model;

  const cols = [
    c("name", "Nombre"),
    c("price", "Precio"),
    c("is_product", "Es un producto?").format((x) => (x ? "Si" : "No")),
    c("stock", "En stock"),
    c("subfamily.name", "Subfamilia"),

    c("created_at", "Creado").format((value, { row }) =>
      moment(value).format("YYYY MM DD h:mm A")
    ),

    {
      type: cells.Buttons,
      buttons: [
        {
          label: "Editar",
          clicked({ row }) {
            const url = route("rates.edit", row.id);
            Inertia.visit(url);
          },
        },
      ],
    },
  ];

  return { rows, cols };
};
