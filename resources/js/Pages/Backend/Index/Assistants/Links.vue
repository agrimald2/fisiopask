<template>
  <!-- Usuarios y Personas -->
  <link-group title="Usuarios y Personas">
    <link-item label="Gráficos y Estadística" icon="fa-chart-bar" @click="go('statistics.index')" />

    <link-item label="Citas" icon="fa-eye" @click="go('doctors.appointments.index')" />

    <link-item label="Pagos recibidos" icon="fa-receipt" @click="go('paymentsPayments.index')" />
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
