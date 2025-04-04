<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SearchHistory;
use Illuminate\Http\JsonResponse;
use OpenApi\Annotations as OA;

/**
 * @OA\Info(
 *   title="API de Clima",
 *   version="1.0.0",
 *   description="API para consultar datos del clima y gestionar el historial de búsquedas."
 * )
 */
class AdminController extends Controller {
    /**
     * @OA\Get(
     *     path="/api/admin/searches",
     *     summary="Obtener historial de búsquedas",
     *     description="Devuelve el historial de búsquedas realizadas por todos los usuarios. Requiere autenticación y rol admin.",
     *     tags={"Admin"},
     *     security={{"sanctum": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Historial de búsquedas obtenido correctamente",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="all_search_history",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="city", type="string", example="Paris"),
     *                     @OA\Property(property="created_at", type="string", format="date-time", example="2025-04-03T19:00:00Z")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(response=403, description="Acceso no autorizado")
     * )
     */
    public function allSearches(Request $request): JsonResponse {
        $all = SearchHistory::with('user:id,name,email')->orderByDesc('created_at')->get();
        return response()->json(['all_search_history' => $all]);
    }
}
