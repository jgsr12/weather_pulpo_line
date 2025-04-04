<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class WeatherService
{
    protected $apiKey;
    public function __construct()
    {
        $this->apiKey = config('services.weatherapi.key');
    }

    public function getWeather(string $city)
    {
        $locale = app()->getLocale();
        $cacheKey = "weather_${city}_${locale}";
        return Cache::remember($cacheKey, 600, function() use ($city, $locale) {
            $url = "http://api.weatherapi.com/v1/current.json";
            $response = Http::get($url, [
                'key' => $this->apiKey,
                'q'   => $city,
                'lang'=> $locale
            ]);
            if ($response->successful()) {
                $data = $response->json();
                return [
                    'city'        => $data['location']['name'],
                    'region'      => $data['location']['region'],
                    'country'     => $data['location']['country'],
                    'local_time'  => $data['location']['localtime'],
                    'temperature' => $data['current']['temp_c'],
                    'humidity'    => $data['current']['humidity'],
                    'wind_kph'    => $data['current']['wind_kph'],
                    'condition'   => $data['current']['condition']['text'],
                ];
            } else {
                return null;
            }
        });
    }
}
