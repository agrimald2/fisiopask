<template>
  <div class="sm:p-2 sm:mx-4">
    <!-- Widget -->
    <div>
      <week-view :schedules="model" />
    </div>
  </div>
</template>

<script>
import UiButton from "@/Shared/UI/Button.vue";

import WeekView from "@/Pages/Backend/Doctors/Schedules/Components/Week";
import LinksLabel from "@/Shared/Backend/Links/Label.vue";
import Separator from "@/Shared/Backend/Links/Separator.vue";

export default {
  props: ["model"],
  components: {
    LinksLabel,
    Separator,
    UiButton,
    WeekView,
  },
};
</script>
