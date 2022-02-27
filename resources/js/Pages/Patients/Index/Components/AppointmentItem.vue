<template>
  <div class="border rounded" style="border-width:2px;" @click="cancel" v-show="model.status!=4">
    <!-- Summary -->
    <div
      class="select-none p-2 cursor-pointer hover:bg-gray-50"
      @click="expanded = !expanded"
    >
      <div class="flex flex-wrap items-center justify-between">
        <div>
          <div class="capitalize text-lg ">
            1
            <!--@TODO
              NUMERITOS EN ORDEN
            -->
          </div>
        </div>
        <div>
          <div class="capitalize text-lg ">
            {{ model.date }}
          </div>
        </div>
        <div>
          <div class="capitalize text-lg ">
             {{ model.start }}
          </div>
        </div>
        <div>
          <div class="capitalize text-lg">
             {{ model.doctor.name }}
          </div>
        </div>
      </div>
    </div>

    <!-- Body 
    <div
      class="border-t py-3 bg-gray-50 text-center"
      v-show="expanded && isPendient && model.status != 4"
    >
      <ui-button
        color="red"
        @click="cancel"
      >Cancelar cita</ui-button>
    </div>
    -->
  </div>
</template>

<style>
  .mlr{
    margin-right:1rem;
  }
</style>

<script>
import UiButton from "@/Shared/Frontend/Button";
import dates from "@/ui/dates.js";

export default {
  props: ["model", "appointmentsLeft"],

  components: {
    UiButton,
  },

  data() {
    return { expanded: false, can_assist: true};
  },

  setup() {
    return { dates };
  },

  methods: {
    cancel() {
      const id = this.model.id;
      const url = route("area.patients.cancel", id);
      this.$inertia.visit(url);
    },
    pay() {
      const id = this.model.id;
      const url = route("area.patients.pay", id);
      this.$inertia.visit(url);
    }
  },

  computed: {
    isPendient() {
      return dates.moment(this.model.date).isAfter();
    },
  },
};
</script>
