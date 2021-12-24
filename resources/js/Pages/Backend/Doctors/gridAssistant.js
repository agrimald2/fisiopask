import { Inertia } from "@inertiajs/inertia";
import { cells, c } from "@ferchoposting/gridie";

export default (props, { attrs }) => {
  const rows = props.model;

  const cols = [
    c("name", "Nombre"),
    c("user.email", "Email"),

    c("sex", "Sexo"),
    c("phone", "Teléfono"),

    c("workspace.name", "Cubículo"),

    {
      type: cells.Buttons,
      attrs: {
        class: "flex gap-2 sm:gap-4 sm:p-2",
      },
      buttons: [
        {
          label: "Bloqueos Freeze",
          clicked({ row }) {
            const url = route("doctors.freezes.index", row.id);
            Inertia.visit(url);
          },
        },
      ],
    },
  ];

  return { rows, cols };
};
