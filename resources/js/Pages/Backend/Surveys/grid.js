import { Inertia } from "@inertiajs/inertia";
import { cells, c } from "@ferchoposting/gridie";

export default (props, { attrs }) => {
    const rows = props.model;
  
    const cols = [
      c("appointment.office", "Oficina"),
      c("office_score", "Puntaje Oficina"),
      c("appointment.doctor.name", "Doctor"),
      c("doctor_score", "Puntaje Doctor"),
      c("service_score", "Puntaje Servicio"),

      c("comment", "Comentario"),

      {
        type: cells.Buttons,
        buttons: [
          {
            label: "Eliminar",
            clicked({ row }) {
              const url = route("surveys.destroy", row.id);
              Inertia.delete(url);
            },
          },
        ],
      },
    ];
  
    return { rows, cols };
};
