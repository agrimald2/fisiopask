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
  props: ["patientRates"],

  components: {
    Gridie,
  },

  setup() {
    const cols = [
      c("name", "Nombre"),
      c("price", "Precio"),
      c("sessions_left", "Citas Restantes"),
      c()
        .type(cells.Buttons)
        .extend({
          buttons: [
            {
              label: "Marcar Asistencia",
              clicked({ row }) {
                if (
                  confirm("Estas seguro?")
                ) {
                  const url = route("patients.rates.assited", row.id);
                  Inertia.get(url);
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
