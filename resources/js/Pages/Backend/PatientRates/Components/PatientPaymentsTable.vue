<template>
  <gridie
    class="w-full text-left"
    :cols="cols"
    :rows="payments"
  ></gridie>
</template>


<script>
import { Gridie, c, cells } from "@ferchoposting/gridie";
import { Inertia } from "@inertiajs/inertia";
import dates from "@/ui/dates";

export default {
  props: ["payments"],

  components: {
    Gridie,
  },

  setup() {
    const cols = [
      c("created_at", "Fecha"),
      c("payment_method", "Método de Pago"),
      c("ammount", "Monto"),
      c("concept", "Concepto"),
      c("patient.fullname", "Cliente"),
      c("", "Estado")
        .extend({
          html: true,
        })
        .format((value, {row}) => {
          let colorClass = (row.deleted_at == null ? "bg-green-400" : "bg-red-400");

          return `<span class="${colorClass} text-white px-2 rounded">${row.deleted_at == null ? 'Valido' : 'Anulado'}</span>`;
        }),
      c().type(cells.Buttons)
        .extend({
          buttons: [
            {
              label: "Anular",
              clicked({ row }) {
                if(row.deleted_at == null)
                {
                  if(confirm("Estás seguro?"))
                  {
                    const url = route("patients.rates.payments.cancel", row.id);
                    Inertia.get(url);
                  }
                }
                else
                {
                  confirm("Este pago fue eliminado el " + dates.dateForHumans(row.deleted_at) + " - " + dates.hourForHumans(row.deleted_at));
                }
              },
            },
          ],
        }),
    ];

    return { cols };
  },
};
</script>
