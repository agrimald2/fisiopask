import { Inertia } from "@inertiajs/inertia";

import { inputs } from "@ferchoposting/formie";

const onDelete = ({ id }) => {
  if (id && confirm("Estas seguro?")) {
    const url = route("freezes.destroy", id);
    Inertia.delete(url);
  }
};

const onSubmit = function (attrs) {
  return ({ id, values }) => {
    if (id) {
      const url = route("freezes.update", id);
      const data = { _method: "PUT", ...values };
      Inertia.post(url, data);
    } else {
      const url = route("doctors.freezes.store", attrs.doctorId);
      Inertia.post(url, values);
    }
  };
};

export default (props, { attrs }) => [
  {
    name: "name",
    label: "Nombre",
    type: "text",
  },

  {
    name: "start",
    label: "Fecha Inicio",
    type: "text",
    attrs: {
      type: "date",
    },
  },

  {
    name: "end",
    label: "Fecha Final",
    type: "text",
    attrs: {
      type: "date",
    },
  },

  {
    type: inputs.Buttons,
    buttons: [
      // Button delete
      function ({ id }) {
        if (id) {
          return {
            label: "Eliminar",
            class: "bg-red-700 text-white",
            clicked: onDelete,
          };
        }
      },

      // Button save
      {
        label: "Guardar",
        type: "submit",
        clicked: onSubmit(attrs),
      },
    ],
  },
];
