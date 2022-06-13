import { Inertia } from "@inertiajs/inertia";
import { cells, Gridie, c } from "@ferchoposting/gridie";

export default (props, { attrs }) => {
  const rows = props.model;

  const cols = [
    c("input_name", "Nombre"),
    c("", "Input Type")
        .extend({
            html: true
        })
        .format(function (row) {
            let str = "null";

            const type = row.input_type;

            if(type == 0) str = "Texto";
            else if(type == 1) str = "Numérico";
            else if(type == 2) str = "Select";
            else if(type == 3) str = "Multi-Select";
            else if(type == 4) str = "Checkbox";

            return `<span>${str}</span>`;
        }),
    c("", "Modelo Relacionado")
        .extend({
            html: true
        })
        .format(function (row) {
            let str = "null";
            const model = row.related_model;

            if(model == 0) str = "Ninguno";
            else if(model == 1) str = "Áreas Afectadas";
            else if(model == 2) str = "Diagnósticos";
            else if(model == 3) str = "Tratamientos";

            return `<span>${str}</span>`;
        })
  ];

  return { rows, cols };
};
