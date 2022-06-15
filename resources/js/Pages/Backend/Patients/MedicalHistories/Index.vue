<template>
  <app-layout title="Historia Médica">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Historia Médica
      </h2>
    </template>
    <app-body>
      <div class="mt-4 capitalize text-center text-xl">
        HISTORIA CLÍNICA
        <br>
        {{ dates.dateForHumans(model.created_at) }}
          -
        {{ dates.hourForHumans(model.created_at) }}
      </div>


      <div class="mt-4">
        <div class="flex items-center gap-4 justify-center text-xl" style="text-transform:uppercase">
          <span style="font-weight:bold"> DOCTOR </span>
          <i class="fas fa-angle-right"></i>
          {{model.doctor.name}} {{model.doctor.lastname}}
        </div>      
      </div>

      <div class="mt-4">
        <div class="flex items-center gap-4 justify-center text-xl" style="text-transform:uppercase">
          <span style="font-weight:bold"> PACIENTE </span>
          <i class="fas fa-angle-right"></i>
          {{model.patient.name}} {{model.patient.lastname1}} {{model.patient.lastname2}}
        </div>      
      </div>

      <div class="mt-4 border rounded p-3">
        <div class="mt-4 text-center text-xl">
          INFORMACIÓN DEL PACIENTE:
        </div>
        <table class="w-full mt-4">
          <tr>
            <th>DNI:</th>
            <td>
              {{ model.patient.dni }}
            </td>
            <th>EDAD:</th>
            <td>
              {{ dates.moment().year() - dates.moment(model.patient.birth_date).year() }} años
            </td>
          </tr>
          <tr>
            <th>SEXO:</th>
            <td>
              {{ model.patient.sex }}
            </td>
            <th>CELULAR:</th>
            <td>
              {{ model.patient.phone }}
            </td>
          </tr>
        </table>
      </div>
      <div class="mt-4 text-center text-xl">
          INFORMACIÓN:
      </div>
      <template v-for="d in data" :key="d.id">
        <div class="mt-8 text-center flex-wrap gap-4 justify-center">
          <h4 style="font-weight:bold"> {{ d.attribute.input_name }} </h4>
          <template v-if="d.attribute.input_type == 0 || d.attribute.input_type == 1">
            <p> {{ d.data }} </p>
          </template>
          <template v-else-if="d.attribute.input_type == 2">
            {{ options(d.data, d.attribute.related_model) }}
          </template>
          <template v-else-if="d.attribute.input_type == 3">
            <template v-for="(opt, index) in splitMult(d.data)" :key="index">
              {{ options(opt, d.attribute.related_model) }}
              <br>
            </template>
          </template>
        </div>
      </template>
      <div class="pb-12"></div>
    </app-body>
  </app-layout>
</template>

<style scoped>
  .ranges{
    text-align:center;
    font-size: 1.2rem;
  }
</style>
<script>
import AppLayout from "@/Layouts/AppLayout.vue";
import AppBody from "@/Shared/Backend/AppBody";
import Grid from "@/Shared/Grid/Grid";
import dates from "@/ui/dates";
import vue3starRatings from "vue3-star-ratings";
import RadialProgressBar from "vue3-radial-progress";

export default {
  props: ["model", 'data', 'treatments', 'areas', 'diagnostics'],
  data: () => {
    return {
      rating: 2.5,
    };
  },
  components: {
    AppLayout,
    AppBody,
    vue3starRatings,
    RadialProgressBar,
    Grid,
  },
  setup() {
    const dashboardLink = route("dashboard");

    return { dates, dashboardLink };
  },
  methods: {
    splitMult(str) {
      let arr = str.split("^");
      arr.splice(-1);
      return arr;
    },
    options(id, modelId) {
      let str = "";
      if(modelId == 1) 
      {
        const model = this.areas.filter(x => x.id == id);
        str = model[0].category + " " + model[0].sub_category;
      }
      else if(modelId == 2) 
      {
        const model = this.diagnostics.filter(x => x.id == id);
        str = model[0].cie_10 + " - " + model[0].name;
      }
      else if(modelId == 3) 
      {
        const model = this.treatments.filter(x => x.id == id);
        str = model[0].name;
      }

      return str;
    },
  },
};
</script>
