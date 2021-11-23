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
      c("qty", "Cant."),
      c()
        .type(cells.Buttons)
        .extend({
          buttons: [
            {
              label: "Eliminar",
              clicked({ row }) {
                if (
                  confirm("Estas seguro?") &&
                  confirm("Esta accion no se puede deshacer")
                ) {
                  const url = route("patients.rates.destroy", row.id);
                  Inertia.delete(url);
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
