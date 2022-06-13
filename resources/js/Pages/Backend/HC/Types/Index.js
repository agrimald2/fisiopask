import { Inertia } from "@inertiajs/inertia";
import { cells, Gridie, c } from "@ferchoposting/gridie";

export default (props, { attrs }) => {
  const rows = props.model;

  const cols = [
    c("name", "Nombre"),
    c().type(cells.Buttons)
        .extend({
            buttons: [
                {
                    label: "Ver",
                    clicked({ row }) {
                        Inertia.visit(route("hc.attributes.index", row.id));
                    }
                },
                {
                    label: "Eliminar",
                    clicked({ row }) {
                        if(confirm("Esta acción no se puede deshacer."))
                        {
                            Inertia.delete(route('hc.destroy', row.id));
                        }
                        //Inertia.visit(route("", row.id));
                    }
                }
            ]
        })
  ];

  return { rows, cols };
};
