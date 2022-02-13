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

class LocationApiService extends AbstractCacheService
{
    private const LOCATION_REDIS_CACHE_KEY = "locationRedisCache";

    private HttpClientInterface $client;

    #[Pure]
    public function __construct(HttpClientInterface $client)
    {
        parent::__construct();

        $this->client = $client;
    }

    /**
     * @param string $ipAddress
     * @param bool $shouldGetFromCache
     * @return array
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function getLocationByIp(string $ipAddress, bool $shouldGetFromCache = true): array
    {
        $cacheItem = $this->getCacheItem(self::LOCATION_REDIS_CACHE_KEY);

        if ($shouldGetFromCache) {
            if ($this->isCached($cacheItem)) {
                return $this->getFromCache($cacheItem);
            }
        }

        $location = $this->getLatestLocationByIp($ipAddress);
        $cacheItem->set($location);
        $this->cache->save($cacheItem);

        return $location;
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     */
    public function getLatestLocationByIp(string $ipAddress): array
    {
        $accessKey = $_SERVER['LOCATION_PROVIDER_ACCESS_KEY'];
        $url = $_SERVER['LOCATION_PROVIDER_URL'];

        $response = $this->client->request(
            'GET',
            sprintf("%s/%s?access_key=%s", $url, $ipAddress, $accessKey)
        );

        return $response->toArray();
    }
}