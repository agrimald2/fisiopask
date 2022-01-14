<template>
  <app-layout title="Citas">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        <div class="flex items-center gap-4">
          <span
            class="underline cursor-pointer"
            @click="$inertia.visit(dashboardLink)"
          >Citas</span>
          <i class="fas fa-angle-right"></i>
          {{ appointment.patient.name }}
        </div>
      </h2>
    </template>

    <app-body>
      <div class="mt-4 capitalize text-center text-xl">
        {{ appointment.status_label}}
        <br>
        {{ dates.dateForHumans(appointment.date) }}
      </div>

      <div class="mt-4">
        <div class="flex items-center gap-4 justify-center text-xl">
          {{ appointment.start }}
          <i class="fas fa-angle-right"></i>
          {{ appointment.end }}
        </div>
      </div>

      <div class="mt-4 border rounded p-3">

        <div class="text-center grid gap-4">
          <div>
            <div class="font-bold">Especialidad:</div> {{ appointment.specialty }}
          </div>
          <div>
            <div class="font-bold">Sucursal:</div> {{ appointment.office }}
          </div>
        </div>

        <div class="mt-4 text-center text-xl">
          Paciente:
        </div>
        <table class="w-full mt-4">
          <tr>
            <th>Nombre:</th>
            <td>
              {{ appointment.patient.fullname }}
            </td>
          </tr>
          <tr>
            <th>DNI:</th>
            <td>
              {{ appointment.patient.dni }}
            </td>
          </tr>
          <tr>
            <th>Fecha de Nacimiento:</th>
            <td>
              {{ dates.dateForHumans(appointment.patient.birth_date) }}
            </td>
          </tr>
          <tr>
            <th>Edad:</th>
            <td>
              {{ dates.moment().year() - dates.moment(appointment.patient.birth_date).year() }} años
            </td>
          </tr>
          <tr>
            <th>Sexo:</th>
            <td>
              {{ appointment.patient.sex }}
            </td>
          </tr>
          <tr>
            <th>Teléfono:</th>
            <td>
              {{ appointment.patient.phone }}
            </td>
          </tr>
        </table>
        <template v-if="rate != null">
          <div class="mt-4 text-center text-xl">
            {{ rate.name }}
          </div>
          <table class="w-full mt-4">
            <tr>
              <th>Precio:</th>
              <td>
                {{ rate.price }}
              </td>
            </tr>
            <tr>
              <th>Cantidad Pagada:</th>
              <td>
                {{ rate.payed }}
              </td>
            </tr>
            <tr>
              <th>Citas Totales:</th>
              <td>
                {{ rate.sessions_total }}
              </td>
            </tr>
            <tr>
              <th>Citas Pagadas:</th>
              <td>
                {{ rate.appointments_paid }}
              </td>
            </tr>
            <tr>
              <th>Citas Asistidas:</th>
              <td>
                {{ rate.appointments_assisted }}
              </td>
            </tr>
            <tr>
              <th>Puede Asistir:</th>
              <td>
                {{ rate.can_assist_string }}
              </td>
            </tr>
          </table>
        </template>
      </div>

      <div class="mt-8 text-center flex flex-wrap gap-4 justify-center">
        <front-button
          color="green"
          v-if="role == 'admin'"
          @click="$inertia.visit(route('patients.historygroup.index', appointment.patient.id))"
        >
          Ver Historial Clínico
        </front-button>

        <front-button
          color="green"
          @click="$inertia.visit(route('doctors.appointments.rates.index', appointment.id))"
        >
          Añadir Productos / Servicios
        </front-button>

        <front-button
          color="yellow"
          @click="$inertia.visit(route('patients.rates.index', appointment.patient.id))"
        >
          Añadir Pagos Manuales
        </front-button>

        <front-button
          color="yellow"
          @click="$inertia.visit(route('patients.rates.link', appointment.patient.id))"
        >
          Generar Link de Pago
        </front-button>
      </div>

      <div class="mt-8 text-center flex flex-wrap gap-4 justify-center">
        <front-button
          color="red"
          v-show="appointment.status != 4 && appointment.is_pending"
          @click="cancelAppointment"
        >
          Cancelar Cita
        </front-button>

        <front-button
          color="green"
          v-show="appointment.status != 4"
          @click="markAssisted"
        >
          Marcar Asistencia
        </front-button>
      </div>

      <div class="pb-12"></div>
    </app-body>

  </app-layout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout.vue";
import AppBody from "@/Shared/Backend/AppBody";

import FrontButton from "@/Shared/Frontend/Button";

import dates from "@/ui/dates";

export default {
  props: ["appointment", "role", "rate"],

  components: {
    AppLayout,
    AppBody,

    FrontButton,
  },

  setup() {
    const dashboardLink = route("dashboard");
    return { dates, dashboardLink };
  },

  methods: {
    cancelAppointment() {
      if (
        confirm("Estas seguro?") &&
        confirm("Esta accion no se puede deshacer, proceder?")
      ) {
        const url = route("doctors.appointments.cancel", this.appointment.id);
        this.$inertia.post(url);
      }
    },
    markAssisted() {
      if(this.rate.can_assist)
      {
        if(confirm("Estás seguro?"))
        {
          const url = route('patients.rates.assisted', this.rate.id);
          this.$inertia.visit(url);
        }
      }
      else
      {
        const url = route('patients.rates.pay', this.rate.id);
        this.$inertia.visit(url);
      }
    },
  },
};
</script>
