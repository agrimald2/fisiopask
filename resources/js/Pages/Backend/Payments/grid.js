import { Inertia } from "@inertiajs/inertia";
import { cells, c } from "@ferchoposting/gridie";

export default (props, { attrs }) => {
  const rows = props.model;

  const cols = [
    c("created_at", "Fecha"),
    c("payment_method", "Método de Pago"),
    c("ammount", "Monto"),
    c("concept", "Concepto"),

    c("patient_rate.rate.name", "Tarifa"),

    c("patient.fullname", "Cliente"),
  ];

  return { rows, cols };
};
