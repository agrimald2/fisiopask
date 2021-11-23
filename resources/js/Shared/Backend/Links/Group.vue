<template>
  <link-container>
    <link-label>{{ title }}</link-label>

    <link-grid>
      <slot></slot>
    </link-grid>

    <link-separator />
  </link-container>
</template>


<script>
import LinkContainer from "./Container.vue";
import LinkGrid from "./Grid.vue";
import LinkLabel from "./Label.vue";
import LinkSeparator from "./Separator.vue";

export default {
  props: ["title"],

  components: {
    LinkContainer,
    LinkGrid,
    LinkLabel,
    LinkSeparator,
  },

  data() {
    return {
      //
    };
  },
};
</script>
