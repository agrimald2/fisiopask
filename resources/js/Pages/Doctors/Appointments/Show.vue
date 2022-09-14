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
          {{ patient.name }}
        </div>
      </h2>
    </template>

    <app-body>
      <div class="mt-4 capitalize text-center text-xl">
        {{ appointment.status_label}} -  <span v-if="patient.is_new" style="color:green"> {{ patient.is_new}} </span>
        <br>
        {{ dates.dateForHumans(appointment.date) }}
      </div>
      <div class="mt-4 capitalize text-center text-xl large-text bold">
          {{doctor.name}} {{doctor.lastname}}
      </div>
      <div class="mt-4">
        <div class="flex items-center gap-4 justify-center text-xl">
          {{ appointment.start }}
          <i class="fas fa-angle-right"></i>
          {{ appointment.end }}
        </div>
      </div>
      <div class="mt-2">
        <div class="flex items-center gap-4 justify-center text-xl">
          <div class="font-bold">Sucursal:</div> {{ appointment.office }}
        </div>
      </div>

      <div class="mt-4 border rounded p-3 text-center">
        <div class="mt-4 text-center text-xl">
          <h1 class="large-text bold"> INFORMACIÓN DEL PACIENTE </h1>
        </div>
        <div class="mt-4">
          <p class="medium-text">
            {{patient.fullname}}
          </p>
          <p v-if="role == 'admin'" class="medium-text">
            {{patient.phone}}
          </p>
          <p class="medium-text">
            {{ patient.dni }} |  {{ patient.sex }} | {{ dates.moment().year() - dates.moment(patient.birth_date).year() }} años
          </p>
        </div>
      </div>

      <template v-if="rate != null">
        <div class="border rounded p-3 text-center">
          <div class="mt-4 text-center text-xl">
            <h1 class="large-text bold"> TARIFA DE LA CITA </h1>
          </div>
          <div class="mt-4 text-center text-xl medium-text">
            {{ rate.name }}
          </div>
          <table class="w-full mt-4">
            <tr class="uppercase">
              <th>Precio:</th>

              <th>Totales:</th>

              <th>Pagadas:</th>

              <th>Asistidas:</th>

              <th>Puede Asistir:</th>
            </tr>
            <tr class="medium-text">
              <td>
                {{ rate.price }}
              </td>

              <td>
                {{ rate.sessions_total }}
              </td>

              <td>
                {{ rate.appointments_paid }}
              </td>

              <td>
                {{ rate.appointments_assisted }}
              </td>

              <td>
                {{ rate.can_assist_string }}
              </td>
            </tr>
          </table>
        </div>
      </template>

      <div class="mt-8 text-center flex flex-wrap gap-4 justify-center">
        <front-button
          color="yellow"
          v-if="role !== 'assistant'"
          @click="$inertia.visit(route('patients.historygroup.index', appointment.patient_id))"
        >
          Ver Historial Clínico
        </front-button>

        <front-button
          color="red"
          v-if="appointment.status == 1 && role !== 'assistant' && role !== 'doctor'"
          @click="cancelAppointment"
        >
          Cancelar Cita
        </front-button>

<!-- v-show="appointment.status != 4 && appointment.is_pending" -->

        <front-button
          color="green"
          v-show="appointment.status != 4 && appointment.status != 3 && role !== 'doctor'"
          @click="markAssisted"
        >
          Marcar Asistencia 2
        </front-button>

        <front-button
          color="red"
          v-if="role == 'admin'"
          v-show="appointment.status == 3"
          @click="markNotAssisted"
        >
          Marcar Inasistencia
        </front-button>

        <!--<front-button
          color="yellow"
          @click="$inertia.visit(route('patients.rates.link', appointment.patient.id))"
        >
          Generar Link de Pago
        </front-button>-->
      </div>

      <div class="mt-8 text-center flex flex-wrap gap-4 justify-center">

        <front-button
          color="yellow"
          v-show="appointment.status != 4 && appointment.status != 3"
          @click="$inertia.visit(route('reschedule.pickOffice', appointment.id))"
        >
          Reprogramar Cita
        </front-button>

        <front-button
          v-if="role == 'admin' || role == 'assistant' "
          color=""
          @click="$inertia.visit(route('doctors.appointments.rates.index', appointment.id))"
        >
          Añadir Productos / Servicios
        </front-button>

        <front-button
          v-if="role == 'admin' || role == 'assistant'"
          color=""
          @click="$inertia.visit(route('patients.rates.index', [appointment.patient_id, appointment.id]))"
        >
          Añadir Pagos
        </front-button>

        <!--front-button
        v-if="rate == null"
          color="green"
          v-show="appointment.status != 4"
          @click="payConstantRate"
        >
          Cobrar Consulta
        </front-button-->
      </div>

      <div class="pb-12"></div>
    </app-body>

    <app-body v-if="role == 'admin'">
      <div class="mt-4 border rounded p-3 text-center">
        <div class="mt-4 text-center text-xl">
          <h1 class="large-text bold"> INFORMACIÓN AUDITORÍA </h1>
        </div>
        <div class="mt-4">
          <p class="medium-text">
            <strong>Fecha de creación</strong>
          </p>
          <!--<p v-if="role == 'admin'" class="medium-text">-->
          <p class="medium-text">
            {{ dates.dateForHumans(appointment.created_at)}} a las {{dates.hourForHumans(appointment.created_at)}}
          </p>

          <p class="medium-text">
            <strong>Por:</strong>
          </p>
          <p v-if="appointment.created_by" class="medium-text">
            {{appointment.created_by}}
          </p>
          <p v-else class="medium-text">
            NO HAY INFO
          </p>

          <p class="medium-text">
            <strong>Cancelado Por:</strong>
          </p>
          <p v-if="appointment.cancel_by" class="medium-text">
            {{appointment.cancel_by}}
          </p>
          <p v-else class="medium-text">
            NO CANCELADO
          </p>

          <p class="medium-text">
            <strong>Reprogramado Por:</strong>
          </p>
          <p v-if="appointment.reeschedule_by" class="medium-text">
            {{appointment.reeschedule_by}}
          </p>
          <p v-else class="medium-text">
            NO REPROGRAMADO
          </p>

          <p class="medium-text">
            <strong>Fecha de ultimo update:</strong>
          </p>
          <p class="medium-text">
            {{ dates.dateForHumans(appointment.updated_at)}} a las {{dates.hourForHumans(appointment.updated_at)}}
          </p>

          <p class="medium-text">
            <strong>Historia Clínica:</strong>
          </p>
          <p class="medium-text">
            <span v-if="appointment.history_created">
              SI
            </span>
            <span v-else>
              NO
            </span>
          </p>
        </div>
      </div>
    </app-body>
  </app-layout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout.vue";
import AppBody from "@/Shared/Backend/AppBody";

import FrontButton from "@/Shared/Frontend/Button";

import dates from "@/ui/dates";

export default {
  props: ["appointment", "doctor", "patient", "role", "rate"],

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
      //if (
      //  confirm("Estas seguro?") &&
      //  confirm("Esta accion no se puede deshacer, proceder?")
      //) {
        const url = route("doctors.appointments.cancel", this.appointment.id);
        this.$inertia.post(url);
      //}
    },
    markAssisted() {
      if(this.rate.can_assist)
      {
        if(confirm("Estás seguro?"))
        {
          const url = route('patients.rates.assisted', [this.rate.id, this.appointment.id]);
          this.$inertia.visit(url);
        }
      }
      else
      {
        const url = route('patients.rates.pay', this.rate.id);
        this.$inertia.visit(url);
      }
    },
    markNotAssisted() {
      if(confirm("Estás seguro?"))
      {
        const url = route('patients.rates.notAssisted', [this.rate.id, this.appointment.id]);
        this.$inertia.visit(url);
      }
    },
    payConstantRate() {
      if (
        confirm("Estas seguro?")
      ) {
        const url = route('patients.constantrate.pay', this.appointment.id);
        this.$inertia.visit(url);
      }
    },
  },
};
</script>
