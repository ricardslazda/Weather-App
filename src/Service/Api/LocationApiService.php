<?php

declare(strict_types=1);

namespace App\Service\Api;

use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class LocationApiService
{
    private const LOCATION_PROVIDER_BASE_URL = "http://api.ipstack.com";

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
    public function getLocationByIp(string $ipAddress): array
    {
        $accessKey = $_SERVER['LOCATION_PROVIDER_ACCESS_KEY'];

        $response = $this->client->request(
            'GET',
            sprintf("%s/%s?access_key=%s", self::LOCATION_PROVIDER_BASE_URL, $ipAddress, $accessKey)
        );

        return $response->toArray();
    }
}