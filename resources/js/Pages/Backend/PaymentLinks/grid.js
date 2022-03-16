import { Inertia } from "@inertiajs/inertia";
import { cells, Gridie, c } from "@ferchoposting/gridie";
import dates from "@/ui/dates.js";
import moment from "moment";

export default (props, { attrs }) => {
  const rows = props.model;

  const cols = [
    c("created_at", "Fecha")
      .class("capitalize")
      .format((v) => dates.dateForHumans(v)),
    c("amount", "Monto"),
    c("is_completed", "Pagado")
        .extend({html: true})
        .format((value, { row }) => {
            var payed = row.is_completed ? "Sí" : "No";
            return `${payed}`;
        }),
    
    c("patient.fullname", "Cliente"),
    //TODO @QR CODE PAYMENTLINK
  ];

  return { rows, cols };
};
