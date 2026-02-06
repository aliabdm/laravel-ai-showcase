<?php

namespace App\Ai\Tools;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Tools\Request;
use Stringable;

class WeatherChecker implements Tool
{
    public function description(): Stringable|string
    {
        return 'Get current weather information for a city. This is a demo tool that returns mock weather data.';
    }

    public function handle(Request $request): Stringable|string
    {
        $city = $request['city'] ?? 'Unknown';

        // Mock weather data for demo purposes
        $mockWeather = [
            'temperature' => random_int(15, 35) . '°C',
            'condition' => ['Sunny', 'Cloudy', 'Partly Cloudy', 'Rainy'][random_int(0, 3)],
            'humidity' => random_int(30, 80) . '%',
            'wind_speed' => random_int(5, 25) . ' km/h',
            'city' => $city,
        ];

        return json_encode($mockWeather);
    }

    public function schema(JsonSchema $schema): array
    {
        return [
            'city' => $schema->string()->required()->description('The city name to get weather for'),
        ];
    }
}
