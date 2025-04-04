<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_register_and_login()
    {
        // Generamos un email único para evitar el error de validación "unique"
        $email = 'tester' . uniqid() . '@example.com';

        // Registro de usuario
        $registerResponse = $this->postJson('/api/register', [
            'name'     => 'Tester',
            'email'    => $email,
            'password' => 'secret123'
        ]);
        $registerResponse->assertStatus(201)
            ->assertJsonStructure(['user', 'token']);

        // Inicio de sesión
        $loginResponse = $this->postJson('/api/login', [
            'email'    => $email,
            'password' => 'secret123'
        ]);
        $loginResponse->assertStatus(200)
            ->assertJsonStructure(['user', 'token']);
        $token = $loginResponse->json('token');

        // Simular respuesta de WeatherAPI para el endpoint /api/weather
        Http::fake([
            'api.weatherapi.com/*' => Http::response([
                'location' => [
                    'name'      => 'Paris',
                    'region'    => 'Île-de-France',
                    'country'   => 'France',
                    'localtime' => '2025-04-03 19:00'
                ],
                'current' => [
                    'temp_c'    => 18.0,
                    'humidity'  => 50,
                    'wind_kph'  => 10.0,
                    'condition' => ['text' => 'Clear']
                ]
            ], 200)
        ]);

        // Acceder a un endpoint protegido (/api/weather)
        $weatherResponse = $this->withHeaders([
            'Authorization' => "Bearer $token"
        ])->getJson('/api/weather?city=Paris');
        $weatherResponse->assertStatus(200)
            ->assertJsonFragment(['city' => 'Paris']);
    }

    /** @test */
    public function non_admin_cannot_access_admin_routes()
    {
        // Crear un usuario sin asignarle ningún rol
        $user = User::factory()->create();
        $token = $user->createToken('test')->plainTextToken;

        // Intentar acceder a un endpoint de admin (middleware 'role:admin')
        $response = $this->withHeaders([
            'Authorization' => "Bearer $token"
        ])->getJson('/api/admin/search-history');
        $response->assertStatus(403);
    }
}
