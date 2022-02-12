<?php

declare(strict_types=1);

namespace App\Service\Api;

use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class WeatherReportApiService
{
    private const WEATHER_REPORT_PROVIDER_BASE_URL = "https://api.openweathermap.org";

    private HttpClientInterface $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     */
    public function getCurrentWeatherReport(float $latitude, float $longitude): array
    {
        $accessKey = $_SERVER['WEATHER_REPORT_PROVIDER_ACCESS_KEY'];

        $response = $this->client->request(
            'GET',
            sprintf("%s/data/2.5/weather?lat=%d&lon=%d&appid=%s", self::WEATHER_REPORT_PROVIDER_BASE_URL, $latitude, $longitude, $accessKey)
        );

        return $response->toArray();
    }
}