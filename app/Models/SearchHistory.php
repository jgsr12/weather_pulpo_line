<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     schema="SearchHistory",
 *     type="object",
 *     title="Historial de Búsqueda",
 *     required={"id", "city", "created_at"},
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="city", type="string", example="Paris"),
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2025-04-03T19:00:00Z")
 * )
 */
class SearchHistory extends Model 
{
    protected $fillable = ['city'];
    
    public function user() {
        return $this->belongsTo(User::class);
    }
}

