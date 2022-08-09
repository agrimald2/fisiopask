import { Inertia } from "@inertiajs/inertia";

import { inputs } from "@ferchoposting/formie";

const onDelete = ({ id }) => {
  if (id && confirm("Estas seguro?")) {
    const url = route("patients.destroy", id);
    Inertia.delete(url);
  }
};

const onSubmit = ({ id, values }) => {
  if (id) {
    const url = route("patients.update", id);
    const data = { _method: "PUT", ...values };
    Inertia.post(url, data);
  } else {
    const url = route("patients.store");
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
    name: "lastname1",
    label: "Apellido Paterno",
    type: "text",
  },

  {
    name: "lastname2",
    label: "Apellido Materno",
    type: "text",
  },

  {
    name: "email",
    label: "Correo Electrónico",
    type: "email",
  },

  {
    name: "document_type",
    label: "Tipo de Documento",
    type: "tel",
    type: inputs.Select,
    options: attrs.documentTypes,
  },

  {
    name: "dni",
    label: "DNI",
    type: "tel",
  },

  {
    name: "birth_date",
    label: "Fecha de Nacimiento",
    type: "date",
  },

  {
    name: "sex",
    label: "Sexo",
    type: inputs.Select,
    options: attrs.sexOptions,
  },

  {
    name: "phone",
    label: "Teléfono",
    type: "tel",
  },

  {
    name: "district",
    label: "Distrito",
    type: "text",
  },

  {
    name: "address",
    label: "Dirección",
    type: "text",
  },

  {
    name: "insurance",
    label: "Seguro de salud",
    type: "text",
  },

  {
    name: "education",
    label: "Educación",
    type: inputs.Select,
    options: attrs.education,
  },

  {
    name: "ocupation",
    label: "Ocupación",
    type: "text",
  },

  {
    name: "religion",
    label: "Religión",
    type: inputs.Select,
    options: attrs.religion,
  },

  {
    name: "birth_place",
    label: "Lugar de nacimiento",
    type: "text",
  },

  {
    name: "status",
    label: "Estado Civil",
    type: inputs.Select,
    options: attrs.status,
  },

  {
    name: "recommendation_id",
    label: "¿Cómo llegó a Anandamida?",
    type: inputs.Select,
    options: attrs.recommendations,
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
