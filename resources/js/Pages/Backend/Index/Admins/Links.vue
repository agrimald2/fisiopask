<template>
  <!-- Usuarios y Personas -->
  <link-group title="Usuarios y Personas">
    <link-item
      label="Doctores"
      icon="fa-user-md"
      @click="go('doctors.index')"
    />

    <link-item
      label="Asistentes"
      icon="fa-hands-helping"
      @click="go('assistants.index')"
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
      label="Cubículos"
      icon="fa-kaaba"
      @click="go('workspaces.index')"
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

  <link-group style="margin-top:-3rem!important;" 
    title="Registros">
    <link-item
        label="Pagos recibidos"
        icon="fa-receipt"
        @click="go('payments.index')"
      />

      <link-item
        label="Links de Pagos"
        icon="fa-credit-card"
        @click="go('paymentlinks.index')"
      />

      <link-item
        label="Encuestas"
        icon="fa-comment-medical"
        @click="go('surveys.index')"
      />

      <link-item
        label="Recomendaciones"
        icon="fas fa-handshake"
        @click="go('recommendation.index')"
      />
  </link-group>

  <!-- System records -->
  <link-group
    title="Registros"
    class=""
  >

  
    <link-item
      label="Gráficos y Estadística"
      icon="fa-chart-bar"
      @click="go('statistics.index')"
    />

    <link-item
      label="Análisis"
      icon="fa-laptop-medical"
      @click="go('analysis.index')"
    />

    <link-item
      label="Áreas afectadas"
      icon="fa-child"
      @click="go('affectedarea.index')"
    />

    <link-item
      label="Diagnósticos"
      icon="fa-file-medical-alt"
      @click="go('diagnostic.index')"
    />

    <link-item
      label="Tratamientos"
      icon="fa-stethoscope"
      @click="go('treatment.index')"
    />
  </link-group>

  <!-- Categorias -->
  <link-group style="margin-top:-3rem!important;" 
    title="Tarifas">
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

  <!-- COV-19 -->
  <link-group style="margin-top:-3rem!important;" 
    title="Pruebas">
    <link-item
      label="Tipos de test"
      icon="fa-microscope"
      @click="go('testTypes.index')"
    />

    <link-item
      label="Tests"
      icon="fa-lungs-virus"
      @click="go('tests.index')"
    />

    <link-item
      label="Compañias"
      icon="fa-hospital-alt"
      @click="go('companies.index')"
    />

    <link-item
      label="Encargados"
      icon="fa-hands-helping"
      @click="go('workers.index')"
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
