<?php

namespace App\Service\Request;

use App\Service\Api\IpAddressService;
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
    private LocationApiService $locationService;
    private WeatherReportApiService $weatherReportService;
    private IpAddressService $ipAddressService;

    public function __construct(
        LocationApiService $locationService,
        WeatherReportApiService $weatherReportService,
        IpAddressService $ipAddressService
    )
    {
        $this->locationService = $locationService;
        $this->weatherReportService = $weatherReportService;
        $this->ipAddressService = $ipAddressService;
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     */
    #[ArrayShape(['location' => "array", 'weatherReport' => "array"])]
    public function processIndexGetRequest(): array
    {
        $ipAddress = $this->ipAddressService->getIpAddress();
        $location = $this->locationService->getLocationByIp($ipAddress);
        $weatherReport = $this->weatherReportService->getCurrentWeatherReport(
            $location['latitude'] ?? 0,
            $location['longitude'] ?? 0
        );

        return [
            'location' => $location,
            'weatherReport' => $weatherReport,
        ];
    }

    /**
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     */
    #[ArrayShape(['location' => "array", 'weatherReport' => "array"])]
    public function processRefreshPostRequest(): array
    {
        $ipAddress = $this->ipAddressService->getIpAddress(false);
        $location = $this->locationService->getLocationByIp($ipAddress, false);
        $weatherReport = $this->weatherReportService->getCurrentWeatherReport(
            $location['latitude'] ?? 0,
            $location['longitude'] ?? 0,
            false
        );

        return [
            'location' => $location,
            'weatherReport' => $weatherReport,
        ];
    }
}