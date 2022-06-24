import { Inertia } from "@inertiajs/inertia";
import { cells, c } from "@ferchoposting/gridie";
import moment from "moment";

export default (props, { attrs }) => {
  const rows = props.model;

  const cols = [
    c("name", "Nombre"),
    c("description", "Descripción"),
    c("", "Tipo")
      .extend({
        html: true
      })
      .format(function (row) {
        let str = "null";
        if(row.type == 0) str = "Cualitativa";
        else if(row.type == 1) str = "Cuantitativa";

        return `<span>${str}</span>`;
      }),
    c("result_count", "Cantidad de resultados"),

    c("created_at", "Creado").format((value, { row }) =>
      moment(value).format("YYYY MM DD h:mm A")
    ),

    {
      type: cells.Buttons,
      buttons: [
        {
          label: "Editar",
          clicked({ row }) {
            const url = route("testTypes.edit", row.id);
            Inertia.visit(url);
          },
        },
      ],
    },
  ];

  return { rows, cols };
};
