<template>
  <link-group title="Area de Doctor">

    <link-item
      label="Ver mi horario"
      icon="fa-eye"
      @click="go('doctors.seeSchedule')"
    />

    <link-item
      label="Mis citas"
      icon="fa-address-book"
      @click="go('doctors.appointments.index')"
    />
  </link-group>
</template>


<script>
import LinkGroup from "@/Shared/Backend/Links/Group";
import LinkItem from "@/Shared/Backend/Links/Item";

export default {
  props: [],

  components: {
    LinkGroup,
    LinkItem,
  },

  methods: {
    go(routeName, params) {
      this.$inertia.visit(route(routeName, params));
    },
  },
};
</script>
