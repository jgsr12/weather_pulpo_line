<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\WeatherService;
use Illuminate\Http\JsonResponse;
use OpenApi\Annotations as OA;

class WeatherController extends Controller {
    protected $weatherService;
    public function __construct(WeatherService $weatherService) {
        $this->weatherService = $weatherService;
    }

    /**
     * @OA\Get(
     *     path="/api/weather",
     *     summary="Obtener datos del clima de una ciudad",
     *     description="Consulta la API de WeatherAPI para obtener datos del clima (temperatura, humedad, viento, etc.). Se requiere autenticación mediante Bearer token.",
     *     tags={"Weather"},
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="city",
     *         in="query",
     *         description="Nombre de la ciudad a consultar",
     *         required=true,
     *         @OA\Schema(type="string", example="Paris")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Datos del clima obtenidos correctamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="city", type="string", example="Paris"),
     *             @OA\Property(property="region", type="string", example="Île-de-France"),
     *             @OA\Property(property="country", type="string", example="France"),
     *             @OA\Property(property="local_time", type="string", example="2025-04-03 19:00"),
     *             @OA\Property(property="temperature", type="number", example=18.0),
     *             @OA\Property(property="humidity", type="number", example=50),
     *             @OA\Property(property="wind_kph", type="number", example=10.0),
     *             @OA\Property(property="condition", type="string", example="Clear")
     *         )
     *     ),
     *     @OA\Response(response=404, description="Ciudad no encontrada o sin datos")
     * )
     */
    public function getWeather(Request $request): JsonResponse {
        $request->validate(['city' => 'required|string']);
        $city = $request->query('city');
        $weatherData = $this->weatherService->getWeather($city);
        if ($weatherData === null) {
            return response()->json(['message' => __('messages.city_not_found')], 404);
        }
        // Registra la búsqueda en el historial del usuario
        $request->user()->searches()->create(['city' => $city]);
        return response()->json([
            'city'        => $weatherData['city'],
            'region'      => $weatherData['region'],
            'country'     => $weatherData['country'],
            'local_time'  => $weatherData['local_time'],
            'temperature' => $weatherData['temperature'],
            'humidity'    => $weatherData['humidity'],
            'wind_kph'    => $weatherData['wind_kph'],
            'condition'   => $weatherData['condition'],
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/history",
     *     summary="Obtener historial de búsquedas",
     *     description="Retorna el historial de búsquedas realizadas por el usuario autenticado.",
     *     tags={"Weather"},
     *     security={{"sanctum": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Historial obtenido correctamente",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="history",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="city", type="string", example="Paris"),
     *                     @OA\Property(property="created_at", type="string", format="date-time", example="2025-04-03T19:00:00Z")
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function history(Request $request): JsonResponse {
        $searches = $request->user()->searches()->orderByDesc('created_at')->get(['city', 'created_at']);
        return response()->json(['history' => $searches]);
    }

    /**
     * @OA\Post(
     *     path="/api/favorites",
     *     summary="Agregar ciudad a favoritos",
     *     description="Marca una ciudad como favorita para el usuario autenticado.",
     *     tags={"Weather"},
     *     security={{"sanctum": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Ciudad a agregar como favorita",
     *         @OA\JsonContent(
     *             required={"city"},
     *             @OA\Property(property="city", type="string", example="Paris")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Ciudad agregada a favoritos",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Ciudad agregada a favoritos."),
     *             @OA\Property(property="favorite", type="object")
     *         )
     *     ),
     *     @OA\Response(response=422, description="Datos inválidos")
     * )
     */
    public function addFavorite(Request $request): JsonResponse {
        $data = $request->validate(['city' => 'required|string']);
        $city = $data['city'];
        $favorite = $request->user()->favorites()->firstOrCreate(['city' => $city]);
        return response()->json([
            'message' => __('messages.favorite_added'),
            'favorite' => $favorite
        ], 201);
    }

    /**
     * @OA\Get(
     *     path="/api/favorites",
     *     summary="Listar ciudades favoritas",
     *     description="Retorna la lista de ciudades marcadas como favoritas por el usuario autenticado.",
     *     tags={"Weather"},
     *     security={{"sanctum": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Favoritos obtenidos correctamente",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="favorites",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="city", type="string", example="Paris"),
     *                     @OA\Property(property="created_at", type="string", format="date-time", example="2025-04-03T19:00:00Z")
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function favorites(Request $request): JsonResponse {
        $favorites = $request->user()->favorites()->get(['city', 'created_at']);
        return response()->json(['favorites' => $favorites]);
    }
}
