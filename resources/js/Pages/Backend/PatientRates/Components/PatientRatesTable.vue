<template>
  <gridie
    class="w-full text-left"
    :cols="cols"
    :rows="patientRates"
  ></gridie>
</template>


<script>
import { Gridie, c, cells } from "@ferchoposting/gridie";
import { Inertia } from "@inertiajs/inertia";

export default {
  props: ["patientRates", "appointment"],

  components: {
    Gridie,
  },

  setup(props) {
    const cols = [
      c("name", "Nombre"),
      c("price", "Precio"),
      c("payed", "Cantidad Pagada"),
      c("sessions_total", "Citas Totales"),
      c("appointments_paid", "Citas Pagadas2"),
      c("appointments_assisted", "Citas Asistidas"),
      c("can_assist_string", "Puede Asistir"),
      c("", "Estado")
        .extend({
            html: true,
        })
        .format(function (row) {
            let colorClass = "bg-green-400";

            if (row.state == 0){
              colorClass = "bg-yellow-400";
            } else if(row.state == 1){
              colorClass = "bg-green-400";
            } else{
              colorClass = "bg-red-400";
            }
            return `<span class="${colorClass} text-white px-2 rounded">${row.status_label}</span>`;
        }),
      c().type(cells.Buttons)
        .extend({
          buttons: [
            {
              label: "Detalles",
              clicked({ row }) {
                const url = route("patients.rates.payments", row.id);
                Inertia.get(url);
              },
            },
          ],
        }),
      c().type(cells.Buttons)
        .extend({
          buttons: [
            {
              label: "Consumir",
              clicked({ row }) {
                if(props.appointment.id == 1)
                {
                  alert("Esta acción solo es posible dentro de una cita.")
                  return;
                }

                if(confirm("Estás seguro?"))
                {
                  if(!row.can_assist)
                  {
                    alert("el paciente no puede asistir");
                  }
                  else
                  {
                    const url = route('patients.rates.assisted', [row.id, props.appointment.id]);
                    Inertia.get(url);
                  }
                }
              },
            },
          ],
        }),
      c().type(cells.Buttons)
        .extend({
          buttons: [
            {
              label: "Abandonar",
              clicked({ row }) {
                if(confirm("Estás seguro?"))
                {
                  const url = route('patients.rates.abandon', row.id);
                  Inertia.get(url);
                }
              },
            },
          ],
        }),
      c().type(cells.Buttons)
        .extend({
          buttons: [
            {
              label: "Anular",
              clicked({ row }) {
                if(confirm("Estás seguro?"))
                {
                  const url = route("patients.rates.cancel", row.id);
                  Inertia.get(url);
                }
              },
            },
          ],
        }),
      c().type(cells.Buttons)
        .extend({
          buttons: [
            {
              label: "Pagar",
              clicked({ row }) {
                const url = route("patients.rates.pay", row.id);
                Inertia.get(url);
              },
            },
          ],
        }),
    ];

    return { cols };
  },
};
</script>
