<?php

declare(strict_types=1);

namespace App\Service\Api;

use App\Service\AbstractCacheService;

class IpAddressService extends AbstractCacheService
{
    private const IP_PROVIDER_URL = "http://checkip.dyndns.com";
    private const IP_REDIS_CACHE_KEY = "ipRedisCache";

    /**
     * @param bool $shouldGetFromCache
     * @return string|null
     */
    public function getIpAddress(bool $shouldGetFromCache = true): ?string
    {
        $cacheItem = $this->getCacheItem(self::IP_REDIS_CACHE_KEY);

        if ($shouldGetFromCache) {
            if ($this->isCached($cacheItem)) {
                return $this->getFromCache($cacheItem);
            }
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