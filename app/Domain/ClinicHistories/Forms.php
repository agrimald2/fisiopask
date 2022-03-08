<?php

namespace App\Domain\ClinicHistories;

class Forms extends BaseForms
{
    public function fisio()
    {
        return $this->form("Historial Clínico", [
            "Nombre",
            "Estatura",
            "Frecuencia cardiaca",
            "IMC",
            "Peso al ingreso"
        ]);
    }


    public function fisioRevision()
    {
        return $this->form('Revision', [
            "Evolucion de la enfermedad",
            "Molestias durante el tratamiento",
            "Medicamentos tomados"
        ]);
    }


    public function anandamida()
    {
        return $this->form("Historial Clínico Principal", [
            "Fecha (dd/mm/aa)",
            "Horas (hh/mm)",
            "Tiempo de enfermedad",
            "Motivo de consulta",
            "Síntomas y signos principales",
            "Apetito",
            "Sed",
            "Sueño",
            "Estado de Animo",
            "Perdida de peso",
            "Diuresis",
            "Deposiciones",
            "Prenatales",
            "Natales",
            "Inmunizaciones",
            "Enfermedades",
            "D.M",
            "HTA",
            "TBC",
            "Asma Bronquial",
            "Diebre Tifoidea",
            "Hepatitis",
            "GP",
            "RC",
            "F.U.R",
            "Medicamentos Habituales",
            "Hospitalizaciones",
            "Cirugía",
            "Transfusiones Sanguíneas",
            "Grupo y Factor Sanguíneo",
            "Alergías conocidas",
            "PAP",
            "FAM. F.D",
            "FAM. HTA",
            "FAM. TBC",
            "FAM. CÁNCER",
            "ALCOHOL",
            "CAFÉ",
            "CIGARROS",
            "DROGAS",
            "OTROS",
            "FUNCIONES VITALES",
            "T°",
            "PA:",
            "FC:",
            "FR",
            "Peso:",
            "Talla",
            "IMC",
            "Examen General",
            "Cabeza y Cuello",
            "Tórax y Pulmones",
            "Ap. Cardiovascular",
            "Abdomen",
            "Sistema Genitourinario",
            "Sistema Nervioso",
            "Extremidades",
            "Examen Preferencia",
            "DIAGNÓSTICO 1",
            "CIE-10",
            "P:",
            "D:",
            "R:",
            "DIAGNÓSTICO 2",
            "CIE-10",
            "P:",
            "D:",
            "R:",
            "DIAGNÓSTICO 3",
            "CIE-10",
            "P:",
            "D:",
            "R:",
            "Examenes de ayuda diagnóstica",
            "Procedimientos especiales y/o interconsultas",
            "Indicaciones Complementarias",
            "Referencia (Lugar y Motivo)",
            "Tratamiento",
        ]);
    }
}
