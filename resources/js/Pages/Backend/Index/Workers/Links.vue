<template>
  <!-- COV-19 -->
  <link-group style="margin-top:-3rem!important;" 
    title="pruebas">
      <link-item
        label="Tests"
        icon="fa-lungs-virus"
        @click="go('tests.index')"
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
