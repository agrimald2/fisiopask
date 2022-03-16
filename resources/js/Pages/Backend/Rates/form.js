import { Inertia } from "@inertiajs/inertia";

import { inputs } from "@ferchoposting/formie";

const onDelete = ({ id }) => {
  if (id && confirm("Estas seguro?")) {
    const url = route("rates.destroy", id);
    Inertia.delete(url);
  }
};

const onSubmit = ({ id, values }) => {
  if (id) {
    const url = route("rates.update", id);
    const data = { _method: "PUT", ...values };
    Inertia.post(url, data);
  } else {
    const url = route("rates.store");
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
    name: "price",
    label: "Precio",
    type: "text",
    attrs: {
      type: "number",
    },
  },

  {
    name: "is_product",
    label: "Es producto?",
    type: inputs.Checkbox,
    default: false,
  },

  {
    name: "stock",
    label: "Stock disponible",
    type: "number",
    attrs: {
      type: "number",
    },
  },

  {
    name: "subfamily_id",
    label: "Subfamilia",
    type: inputs.Select,
    options: attrs.subfamilies,
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
