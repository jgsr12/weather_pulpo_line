<?php

namespace Tests\Feature;

use App\Services\WeatherService;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class WeatherServiceTest extends TestCase
{
    /** @test */
    public function it_returns_weather_data_for_valid_city()
    {
        $fakeApiResponse = [
            'location' => [
                'name'      => 'London',
                'region'    => 'City of London',
                'country'   => 'United Kingdom',
                'localtime' => '2025-04-03 19:00'
            ],
            'current' => [
                'temp_c'    => 15.0,
                'humidity'  => 70,
                'wind_kph'  => 5.5,
                'condition' => ['text' => 'Sunny']
            ]
        ];

        Http::fake([
            'api.weatherapi.com/*' => Http::response($fakeApiResponse, 200)
        ]);

        $service = new WeatherService();
        $result = $service->getWeather('London');
        $this->assertNotNull($result);
        $this->assertEquals('London', $result['city']);
        $this->assertEquals(15.0, $result['temperature']);
        $this->assertEquals('Sunny', $result['condition']);
    }
}
