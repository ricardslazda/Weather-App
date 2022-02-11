<?php

declare(strict_types=1);

namespace App\Service\Api;

class IpAddressApiService
{
    private const IP_PROVIDER_URL = 'http://checkip.dyndns.com/';

    public function getIpAddress(): string
    {
        $externalContent = file_get_contents(self::IP_PROVIDER_URL);
        preg_match('/Current IP Address: \[?([:.0-9a-fA-F]+)?/', $externalContent, $m);

        return $m[1];
    }
}