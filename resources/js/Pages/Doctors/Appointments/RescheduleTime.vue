<template>
  <Layout>
    <form @submit.prevent="onSubmit">
      <ui-container>
        <!-- Title -->
        <div class="text-center">
          <div class="text-2xl uppercase tracking-wider text-gray-800">
            {{ dates.dateForHumans(date) }}
          </div>

          <!-- Show office -->
          <div v-if="office" class="mt-3">
            <div class="text-lg">{{ office.name }}</div>
            <div class="text-gray-400">{{ office.address }}</div>
          </div>
        </div>

        <!-- Filters -->
        <div class="mt-6" v-if="groupedSchedules.length > 0">
          <filters v-model="filters" :specialtyOptions="specialtyOptions" :doctorOptions="doctorOptions" />
        </div>

        <!-- Schedules -->
        <ui-loading v-if="loading" class="py-8"></ui-loading>
        <div class="mt-6" v-else>
          <schedules @picked="onPicked" :groupedSchedules="groupedSchedules" :filter="filterSchedules" />
        </div>

        <!-- Back -->
        <div class="mt-8 text-center grid">
          <ui-button type="button" class="bg-red-500 border-red-700 text-white" @click="back()">
            <div class="flex items-center justify-center">
              <i class="fas fa-angle-left pr-3 bold uppercase"></i>
              CAMBIAR FECHA 3
            </div>
          </ui-button>
        </div>
        <div class="uppercase font-bold tracking-wide">
          {{ filters }}
        </div>
        <hr>
        <div class="uppercase font-bold tracking-wide">
          {{ groupedSchedules }}
        </div>

      </ui-container>
    </form>
  </Layout>
</template>

<script>
import Layout from "../../Frontend/BookAppointment/Layout/Layout.vue";
import UiContainer from "../../Frontend/BookAppointment/UI/Container.vue";
import UiLoading from "../../Frontend/BookAppointment/UI/Loading.vue";
import UiButton from "../../Frontend/BookAppointment/UI/Button.vue";

import Filters from "../../Frontend/BookAppointment/PickTime/Filters.vue";
import Schedules from "../../Frontend/BookAppointment/PickTime/Schedules.vue";

import dates from "@/ui/dates.js";

export default {
  props: ['filters', 'date', 'groupedSchedules', 'appointment', 'office'],

  components: {
    Layout,

    UiContainer,
    UiLoading,
    UiButton,

    Filters,
    Schedules,
  },

  setup() {
    return {
      dates
    };
  },

  data() {
    return {
      loading: false,
      filters: this.$props.filters,
    }
  },

  computed: {
    doctorOptions() {
      const doctors = {};

      this.groupedSchedules.forEach((x) => {
        x.schedules.forEach((y) => {
          const doctor_id = y.doctor_id;
          doctors[doctor_id] = y.doctor;
        });
      });
      return doctors;
    },

    specialtyOptions() {
      const specialties = {};

      this.groupedSchedules.forEach((x) => {
        x.schedules.forEach((schedule) => {
          Object.keys(schedule.specialties).forEach((specialty_id) => {
            specialties[specialty_id] = schedule.specialties[specialty_id];
          });
        });
      });

      return specialties;
    }
  },

  methods: {
    filterSchedules(model) {
      let schedules = [...model];

      // Doctor id
      schedules = schedules.filter(
        (schedule) =>
          !this.filters.doctorId || this.filters.doctorId == schedule.doctor_id
      );

      // Specialty id
      schedules = schedules.filter(
        (schedule) =>
          !this.filters.specialtyId ||
          Object.keys(schedule.specialties).includes(this.filters.specialtyId)
      );

      return schedules;
    },

    back() {
      this.$inertia.visit(route("reschedule.pickDay", [this.appointment, this.office]));
    },

    onPicked(scheduleItem) {
      this.loading = true;

      const url = route("reschedule.postTime", [this.appointment, this.office]);

      const schedule_id = scheduleItem.id;
      const data = {
        'schedule_id': schedule_id,
        'date': this.date,
      };

      this.$inertia.post(url, data, {
        onFinish: () => (this.loading = false),
      });
    }
  }
}
</script>