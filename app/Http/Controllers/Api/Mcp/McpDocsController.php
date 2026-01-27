<?php

namespace App\Http\Controllers\Api\Mcp;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;

class McpDocsController extends Controller
{
    /**
     * Mostrar la interfaz de Swagger UI
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('swagger.index');
    }

    /**
     * Retornar el archivo de especificación OpenAPI
     *
     * @return Response
     */
    public function spec()
    {
        $path = storage_path('api-docs/mcp-swagger.yaml');

        if (!file_exists($path)) {
            abort(404, 'Archivo de especificación no encontrado');
        }

        $content = file_get_contents($path);

        return response($content, 200, [
            'Content-Type' => 'application/x-yaml',
            'Cache-Control' => 'no-cache',
        ]);
    }
}
