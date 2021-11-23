<template>
  <div v-if="links.length > 3">
    <div class="flex flex-wrap -mb-1">
      <template
        v-for="(link, p) in links"
        :key="p"
      >
        <div
          v-if="link.url === null"
          class="bg-white mr-1 mb-1 px-4 py-3 text-sm leading-4 text-gray-400 border rounded"
          v-html="link.label"
        />
        <inertia-link
          v-else
          class="bg-white mr-1 mb-1 px-4 py-3 text-sm leading-4 border rounded focus:border-indigo-500 focus:text-indigo-500"
          :class="{ 'bg-blue-700 text-white': link.active, 'hover:bg-gray-100': !link.active }"
          :href="link.url"
          v-html="link.label"
          preserve-scroll
          :data="data"
        />
      </template>
    </div>
  </div>
</template>

<script>
import { Link } from "@inertiajs/inertia-vue3";

export default {
  props: {
    links: Array,
    data: Object,
  },
  components: {
    InertiaLink: Link,
  },
};
</script>
