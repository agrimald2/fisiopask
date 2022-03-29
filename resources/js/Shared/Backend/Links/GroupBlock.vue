<template>
  <link-container>
    <link-grid>
      <slot></slot>
    </link-grid>

    <link-separator />
  </link-container>
</template>


<script>
import LinkContainer from "./Container.vue";
import LinkBlock from "./Block.vue";
import LinkLabel from "./Label.vue";
import LinkSeparator from "./Separator.vue";

export default {
  props: ["title"],

  components: {
    LinkContainer,
    LinkBlock,
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
