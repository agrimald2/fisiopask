import { cells, Gridie, c } from "@ferchoposting/gridie";
import moment from "moment";


export default (props, { attrs }) => {
  const rows = props.model;

  const cols = [
    c("created_at", "Creado"),
    c("payment_method", "Método de Pago"),
    c("ammount", "Monto"),
    c("concept", "Concepto"),

    c("patient_rate.name", "Tarifa"),

    c("patient.fullname", "Cliente"),
  ];

  return { rows, cols };
};
