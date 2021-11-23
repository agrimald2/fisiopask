<template>
  <!-- Usuarios y Personas -->
  <link-group title="Usuarios y Personas">
    <link-item
      label="Doctores"
      icon="fa-user-md"
      @click="go('doctors.index')"
    />

    <link-item
      label="Pacientes"
      icon="fa-hospital-user"
      @click="go('patients.index')"
    />

    <link-item
      label="Sucursales"
      icon="fa-building"
      @click="go('offices.index')"
    />

    <link-item
      label="Especialidades"
      icon="fa-briefcase-medical"
      @click="go('doctorSpecialties.index')"
    />

    <link-item
      label="Citas"
      icon="fa-eye"
      @click="go('doctors.appointments.index')"
    />
  </link-group>

  <!-- System records -->
  <link-group
    title="Registros"
    class="hidden"
  >
    <link-item
      label="Historiales Médicos"
      icon="fa-book-medical"
      @click="go('')"
    />

    <link-item
      label="Pagos recibidos"
      icon="fa-receipt"
      @click="go('')"
    />

    <link-item
      label="Diagnósticos"
      icon="fa-stethoscope"
      @click="go('')"
    />

    <link-item
      label="Encuestas"
      icon="fa-comment-medical"
      @click="go('')"
    />
  </link-group>

  <!-- Categorias -->
  <link-group title="Categorías y etiquetas">
    <link-item
      label="Tarifas"
      icon="fa-dollar-sign"
      @click="go('rates.index')"
    />

    <link-item
      label="Métodos de pago"
      icon="fa-donate"
      @click="go('paymentMethods.index')"
    />

    <link-item
      label="Familias"
      icon="fa-tags"
      @click="go('families.index')"
    />

    <link-item
      label="Subfamilias"
      icon="fa-tags"
      @click="go('subfamilies.index')"
    />
  </link-group>
</template>


<script>
import LinkGroup from "@/Shared/Backend/Links/Group";
import LinkItem from "@/Shared/Backend/Links/Item";

export default {
  props: [],

  components: {
    LinkGroup,
    LinkItem,
  },

  methods: {
    go(routeName) {
      const url = route(routeName);
      this.$inertia.visit(url);
    },
  },
};
</script>
