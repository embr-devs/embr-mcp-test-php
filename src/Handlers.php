<?php

declare(strict_types=1);

namespace EmbrMcpTest;

use PhpMcp\Server\Attributes\McpTool;

final class Handlers
{
    /**
     * Get the current weather for a city.
     */
    #[McpTool(name: 'get_weather', description: 'Get the current weather for a city.')]
    public function getWeather(string $location): array
    {
        $conditions = ['sunny', 'cloudy', 'rainy', 'snowy', 'windy'];
        $h = 0;
        foreach (str_split($location) as $c) {
            $h = ($h * 31 + ord($c)) & 0x7FFFFFFF;
        }
        return [
            'location' => $location,
            'condition' => $conditions[$h % count($conditions)],
            'temperature_c' => (float) (($h % 40) - 10) + 0.5,
        ];
    }

    /**
     * Get the current time in an IANA timezone (e.g. America/New_York).
     */
    #[McpTool(name: 'get_time', description: 'Get the current time in an IANA timezone.')]
    public function getTime(string $timezone = 'UTC'): array
    {
        $dt = new \DateTime('now', new \DateTimeZone($timezone));
        return [
            'timezone' => $timezone,
            'time' => $dt->format(\DateTime::RFC3339),
        ];
    }
}
