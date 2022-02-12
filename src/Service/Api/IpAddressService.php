<?php

declare(strict_types=1);

namespace App\Service\Api;

use App\Service\AbstractCacheService;
use Psr\Cache\InvalidArgumentException;

class IpAddressService extends AbstractCacheService
{
    private const IP_PROVIDER_URL = "http://checkip.dyndns.com";
    private const IP_REDIS_CACHE_KEY = "ipRedisCache";

    /**
     * @throws InvalidArgumentException
     */
    public function getIpAddress(): ?string
    {
        $cacheItem = $this->getCacheItem(self::IP_REDIS_CACHE_KEY);

        if ($this->isCached($cacheItem)) {
            return $this->getFromCache($cacheItem);
        }

        $ipAddress = $this->getLatestIpAddress();
        $cacheItem->set($ipAddress);
        $this->cache->save($cacheItem);

        return $ipAddress;
    }

    private function getLatestIpAddress(): string
    {
        $externalContent = file_get_contents(self::IP_PROVIDER_URL);
        preg_match('/Current IP Address: \[?([:.0-9a-fA-F]+)?/', $externalContent, $matches);

        return $matches[1];
    }
}