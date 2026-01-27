<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class McpTokenAuth
{
    /**
     * Handle an incoming request.
     *
     * Valida que el request contenga un token Bearer valido
     * configurado en la variable de entorno MCP_API_TOKEN
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $token = $request->bearerToken();

        if (!$token) {
            return response()->json([
                'success' => false,
                'error' => [
                    'code' => 'MISSING_TOKEN',
                    'message' => 'Token de autenticacion no proporcionado. Use el header Authorization: Bearer {token}',
                ],
            ], 401);
        }

        $validToken = config('services.mcp.api_token');

        if (!$validToken) {
            return response()->json([
                'success' => false,
                'error' => [
                    'code' => 'TOKEN_NOT_CONFIGURED',
                    'message' => 'El token MCP no esta configurado en el servidor',
                ],
            ], 500);
        }

        if (!hash_equals($validToken, $token)) {
            return response()->json([
                'success' => false,
                'error' => [
                    'code' => 'INVALID_TOKEN',
                    'message' => 'Token de autenticacion invalido',
                ],
            ], 401);
        }

        return $next($request);
    }
}
