import { Inertia } from "@inertiajs/inertia";
import { cells, c } from "@ferchoposting/gridie";

export default (props, { attrs }) => {
  const rows = props.model;

  const cols = [
    c("name", "Nombre"),
    c("user.email", "Email"),

    {
      type: cells.Buttons,
      attrs: {
        class: "flex gap-2 sm:gap-4 sm:p-2",
      },
      buttons: [
        {
          label: "Editar",
          clicked({ row }) {
            const url = route("assistants.edit", row.id);
            Inertia.visit(url);
          },
        },
      ],
    },
  ];

  return { rows, cols };
};
