<template>
  <div class="border rounded">
    <!-- Summary -->
    <div
      class="select-none p-2 cursor-pointer hover:bg-gray-50"
      @click="expanded = !expanded"
    >
      <div class="flex flex-wrap items-center justify-between">
        <div>
          <div class="mt-3 text-sm medium-text">
            {{ model.test_type.name }}
          </div>
        </div>
      </div>
      <div class="flex gap-4 items-center">
        Doctor: {{ model.doctor.fullname }}
        <br>
        Resultado: {{ model.result }}
      </div>
    </div>
  </div>
</template>

<script>
import UiButton from "@/Shared/Frontend/Button";

export default {
  props: ["model", "appointmentsLeft"],

  components: {
    UiButton,
  },
};
</script>
