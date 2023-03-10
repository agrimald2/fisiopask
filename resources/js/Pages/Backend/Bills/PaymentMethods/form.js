import { Inertia } from "@inertiajs/inertia";

import { inputs } from "@ferchoposting/formie";

const onDelete = ({ id }) => {
  if (id && confirm("Estas seguro?")) {
    const url = route("billspaymentmethods.destroy", id);
    Inertia.delete(url);
  }
};

const onSubmit = ({ id, values }) => {
  if (id) {
    const url = route("billspaymentmethods.update", id);
    const data = { _method: "PUT", ...values };
    Inertia.post(url, data);
  } else {
    const url = route("billspaymentmethods.store");
    Inertia.post(url, values);
  }
};

export default (props, { attrs }) => [
  {
    name: "name",
    label: "Nombre",
    type: "text",
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
        clicked: onSubmit,
      },
    ],
  },
];
