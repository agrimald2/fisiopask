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
        return $this->form("Historial Clínico", [
            "Capacidad diferente",
            "Síntomas",
            "Historial de la enfermedad actual",
            "Hábitos nocivos",
            "Antecedentes",
            "Medicación actual",
            "Frecuencia cardiaca",
            "Estatura",
            "IMC",
            "Presión arterial",
            "Frecuencia respiratoria",
            "Peso al ingreso",
            "Biotipo",
            "Temp. Oral, Axilar, Rectal",
            "Estado general",
            "Piel y anexos",
            "Mucosas",
            "TCSC",
            "Sistema linfático",
            "Cabeza",
            "Ojos: Pupilas, fondo de ojo",
            "Oídos",
            "Naríz",
            "Boca labios, lengua dientes",
            "Faringe",
            "Cuello: Adenopatía bruits, bocio, nódulos, dolor",
            "Torax: Inspección percusión, auscultación",
            "Mamas",
            "Cardiovascular: FC ritmo, amplitud, pulsos, peritéricos, inspección, etc.",
            "Abdomen: inspección auscultación, percusión, palpitación, etc.",
            "Genitales: Inspección, lesiones, masas, secresion, etc.",
            "Osteoarticular: Inspección, deformaciones, dolor, eritema, etc.",
            "Neurológico: Sensorio, estado mental, expresividad, sensibilidad, motricidad y signos",
            "Diagnósticos",
            "Plan inicial",
            "Pronósticos",
            "Observaciones",
        ]);
    }
}
