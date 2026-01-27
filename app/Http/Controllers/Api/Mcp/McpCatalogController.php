<?php

namespace App\Http\Controllers\Api\Mcp;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\DoctorSpecialty;
use Illuminate\Http\JsonResponse;

class McpCatalogController extends Controller
{
    /**
     * Listar todos los doctores con sus especialidades
     *
     * @return JsonResponse
     */
    public function doctors(): JsonResponse
    {
        $doctors = Doctor::query()
            ->with('specialties')
            ->orderBy('name')
            ->get()
            ->map(function ($doctor) {
                return [
                    'id' => $doctor->id,
                    'name' => $doctor->name,
                    'lastname' => $doctor->lastname,
                    'fullname' => $doctor->fullname,
                    'specialties' => $doctor->specialties->map(function ($specialty) {
                        return [
                            'id' => $specialty->id,
                            'name' => $specialty->name,
                        ];
                    }),
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $doctors,
        ]);
    }

    /**
     * Listar todas las especialidades medicas
     *
     * @return JsonResponse
     */
    public function specialties(): JsonResponse
    {
        $specialties = DoctorSpecialty::query()
            ->orderBy('name')
            ->get()
            ->map(function ($specialty) {
                return [
                    'id' => $specialty->id,
                    'name' => $specialty->name,
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $specialties,
        ]);
    }
}
