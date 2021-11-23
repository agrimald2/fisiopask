<template>
  <div class="mt-12 sm:p-2 sm:mx-4">
    <links-label>
      Citas Pendientes
    </links-label>

    <!-- Widget -->
    <div>
      <grid :rows="model" />
    </div>

    <div class="mt-4 text-center">
      <ui-button @click="$inertia.visit(route('doctors.appointments.index'))">
        Ver todas las citas
      </ui-button>
    </div>

    <!-- Separator -->
    <separator />
  </div>
</template>

<script>
import UiButton from "@/Shared/UI/Button.vue";

import Grid from "./Grid.vue";
import LinksLabel from "@/Shared/Backend/Links/Label.vue";
import Separator from "@/Shared/Backend/Links/Separator.vue";

export default {
  props: ["model"],
  components: {
    Grid,
    LinksLabel,
    Separator,
    UiButton,
  },
};
</script>
