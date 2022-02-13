<?php

declare(strict_types=1);

namespace App\Service\Api;

use App\Service\AbstractCacheService;
use JetBrains\PhpStorm\Pure;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class WeatherReportApiService extends AbstractCacheService
{
    private const WEATHER_REPORT_PROVIDER_BASE_URL = "https://api.openweathermap.org";
    private const WEATHER_REDIS_CACHE = "weatherRedisCache";

    private HttpClientInterface $client;

    #[Pure]
    public function __construct(HttpClientInterface $client)
    {
        parent::__construct();

        $this->client = $client;
    }

    /**
     * @param float $latitude
     * @param float $longitude
     * @param bool $shouldGetFromCache
     * @return array
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function getCurrentWeatherReport(float $latitude, float $longitude, bool $shouldGetFromCache = true): array
    {
        $cacheItem = $this->getCacheItem(self::WEATHER_REDIS_CACHE);

        if ($shouldGetFromCache) {
            if ($this->isCached($cacheItem)) {
                return $this->getFromCache($cacheItem);
            }
        }

        $weatherReport = $this->getLatestCurrentWeatherReport($latitude, $longitude);
        $cacheItem->set($weatherReport);
        $this->cache->save($cacheItem);

        return $weatherReport;
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     */
    public function getLatestCurrentWeatherReport(float $latitude, float $longitude): array
    {
        $accessKey = $_SERVER['WEATHER_REPORT_PROVIDER_ACCESS_KEY'];

        $response = $this->client->request(
            'GET',
            sprintf("%s/data/2.5/weather?lat=%d&lon=%d&appid=%s", self::WEATHER_REPORT_PROVIDER_BASE_URL, $latitude, $longitude, $accessKey)
        );

        return $response->toArray();
    }
}