<template>
  <layout :url="route('area.patients.index')">
    <slot></slot>
  </layout>
</template>

<script>
import Layout from "@/Pages/Frontend/BookAppointment/Layout/Layout";

export default {
  components: {
    Layout,
  },
};
</script>
