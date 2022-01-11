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
          INFORMACIÓN MÉDICA:
      </div>
      <div class="mt-8 text-center flex-wrap gap-4 justify-center">
          <h4 style="font-weight:bold"> ANTECEDENTES </h4>
          <br>
          <p>
            {{ model.background }}
          </p>
      </div>

      <div class="mt-8 text-center flex-wrap gap-4 justify-center">
          <h4 style="font-weight:bold"> ADVERTENCIAS </h4>
          <br>
          <p>
            {{ model.warnings }}
          </p>
      </div>

      <div class="mt-8 text-center flex-wrap gap-4 justify-center">
          <h4 style="font-weight:bold"> DESCRIPCIÓN </h4>
          <br>
          <p>
            {{ model.description}}
          </p>
      </div>

      <div class="mt-4 border rounded p-3">
        <table class="w-full mt-4" style="text-align:left">
          <tr>
            <th>TRATAMIENTOS:</th>
            <template v-for="treatment in model.history_treatments" :key="treatment.id">
              <tr>
                {{ treatment.treatment.name}} - {{ treatment.treatment.description}}
              </tr>
            </template>
            <th>DIAGNÓSTICO:</th>
            <td>
              {{ model.diagnostic.cie_10 }} - {{ model.diagnostic.name }}
            </td>
          </tr>
          <br>
          <tr>
            <th>ANÁLISIS SUGERIDOS:</th>
            <td>
              {{ model.analysis.name }} / {{ model.analysis.description}}
            </td>
            <th>ARÉA AFECTADA:</th>
            <td>
              {{ model.affected_area.category }} /
              {{ model.affected_area.sub_category }}
              
            </td>
          </tr>
        </table>
      </div>
      <div class="mt-4 border rounded p-3">
        <table class="w-full mt-4" style="text-align:left">
          <tr class="ranges">
            <th>ESCALA DE DOLOR</th>
            <th>ESCALA DE FUERZA</th>
            <th>RANGO ARTICULAR</th>
            <th>PROGRESO RECUPERACIÓN</th>
          </tr>
          <tr class="ranges">
            <td>{{model.pain_scale}}</td>
            <td>{{model.force_scale}}</td>
            <td>{{model.joint_range}}</td>
            <td>{{model.recovery_progress}} %</td>
          </tr>
        </table>
      </div>

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
  props: ["model"],
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

  mounted()
  {
    console.log(this.model);
  },
  setup() {
    const dashboardLink = route("dashboard");

      const completedSteps = 5;
      const totalSteps = 10;
      const name = "Hola";
      const description = "Bola";

    return { dates, dashboardLink, completedSteps,totalSteps,name,description  };
  },
};
</script>
