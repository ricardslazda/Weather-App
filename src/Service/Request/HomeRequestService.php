<?php

namespace App\Service\Request;

use App\Service\Api\IpAddressApiService;
use App\Service\Api\LocationApiService;
use App\Service\Api\WeatherReportApiService;
use JetBrains\PhpStorm\ArrayShape;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class HomeRequestService
{
    private LocationApiService $locationApiService;
    private WeatherReportApiService $weatherReportApiService;
    private IpAddressApiService $ipAddressApiService;

    public function __construct(
        LocationApiService $locationApiService,
        WeatherReportApiService $weatherReportApiService,
        IpAddressApiService $ipAddressApiService
    ) {
        $this->locationApiService = $locationApiService;
        $this->weatherReportApiService = $weatherReportApiService;
        $this->ipAddressApiService = $ipAddressApiService;
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     */
    #[ArrayShape(['location' => "array", 'weatherReport' => "string"])]
    public function processIndex(): array
    {
        $ipAddress = $this->ipAddressApiService->getIpAddress();
        $location = $this->locationApiService->getLocationByIp($ipAddress);
        $weatherReport = $this->weatherReportApiService->getCurrentWeatherReport($location['latitude'], $location['longitude']);

        return [
            'location' => $location,
            'weatherReport' => $weatherReport,
        ];
    }
}