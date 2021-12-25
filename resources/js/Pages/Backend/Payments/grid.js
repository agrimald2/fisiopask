import { Inertia } from "@inertiajs/inertia";
import { cells, Gridie, c } from "@ferchoposting/gridie";
import dates from "@/ui/dates.js";
import moment from "moment";


export default (props, { attrs }) => {
  const rows = props.model;

  const cols = [
    c("created_at", "Creado"),
    c("payment_method", "Método de Pago"),
    c("ammount", "Monto"),
    c("concept", "Concepto"),

    c("patient_rate.rate.name", "Tarifa"),

    c("patient.fullname", "Cliente"),

  /*c("", "Horario")
    .extend({ html: true })
    .format((value, { row }) => {
      return `<a href="https://gay.com">${row.created_at} ASJDKHASJ</a>`;
    }),*/
  ];

  return { rows, cols };
};
