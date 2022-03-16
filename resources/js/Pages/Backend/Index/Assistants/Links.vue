<template>
  <!-- Usuarios y Personas -->
  <link-group title="Usuarios y Personas">
    <link-item
      label="Doctores"
      icon="fa-user-md"
      @click="go('doctors.index')"
    />

    <link-item
      label="Citas"
      icon="fa-eye"
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
    go(routeName) {
      const url = route(routeName);
      this.$inertia.visit(url);
    },
  },
};
</script>
