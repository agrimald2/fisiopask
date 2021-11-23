<template>
  <app-layout title="Form">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        <div class="flex items-center">
          <div
            class="underline cursor-pointer"
            @click="$inertia.visit(title.url)"
          >
            {{ title.resource }}
          </div>
          <i class="fas fa-angle-right mx-3"></i>
          {{ title.action }}
        </div>

      </h2>
    </template>

    <div>
      <div class="max-w-xl mx-auto py-10 sm:px-6 lg:px-8">

        <div class="px-4 py-4 bg-white rounded shadow-sm">
          <formie
            :form="formObject"
            :model="model"
            :errors="$page.props.errors"
          />
        </div>

      </div>

      <!-- Custom component -->
      <div v-if="component">
        <jet-section-border />

        <component
          :is="component"
          v-bind="$props"
        />
      </div>

    </div>
  </app-layout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout.vue";
import JetSectionBorder from "@/Jetstream/SectionBorder.vue";

import { Formie } from "@ferchoposting/formie";

export default {
  props: ["model", "title", "form", "component"],

  components: {
    AppLayout,
    JetSectionBorder,

    Formie,
  },

  setup(props, setupContext) {
    const form = require(`@/Pages/${props.form}`).default;

    return {
      formObject: form(props, setupContext),

      component: props.component
        ? require(`@/Pages/${props.component}`).default
        : null,
    };
  },

  methods: {},
};
</script>
