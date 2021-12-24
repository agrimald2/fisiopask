import { Inertia } from "@inertiajs/inertia";
import { cells, c } from "@ferchoposting/gridie";

export default (props, { attrs }) => {
    const rows = props.model;
  
    const cols = [
      c("appointment.office", "Oficina"),
      c("appointment.doctor.name", "Doctor"),
      c("appointment.patient.name", "Paciente"),
      c("office_score", "Oficina"),
      c("doctor_score", "Doctor"),
      c("service_score", "Servicio"),

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
