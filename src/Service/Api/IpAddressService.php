<?php

declare(strict_types=1);

namespace App\Service\Api;

use Psr\Cache\CacheItemInterface;
use Psr\Cache\InvalidArgumentException;
use Symfony\Contracts\Cache\CacheInterface;

class IpAddressService
{
    private const IP_PROVIDER_URL = "http://checkip.dyndns.com";
    private const IP_REDIS_CACHE_KEY = "ipRedisCache";

    private CacheInterface $cache;

    public function __construct(CacheInterface $locationServiceCache)
    {
        $this->cache = $locationServiceCache;
    }

    /**
     * @throws InvalidArgumentException
     */
    public function getIpAddress(): ?string
    {
        $cacheItem = $this->getCacheItem();

        if ($this->isIpAddressCached($cacheItem)) {
            return $this->getIpAddressFromCache($cacheItem);
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

    private function getIpAddressFromCache(CacheItemInterface $cacheItem): ?string
    {
        return $cacheItem->get() ?? null;
    }

    /**
     * @throws InvalidArgumentException
     */
    private function getCacheItem(): CacheItemInterface
    {
        return $this->cache->getItem(self::IP_REDIS_CACHE_KEY);
    }

    private function isIpAddressCached(CacheItemInterface $cacheItem): bool
    {
        return (bool)$this->getIpAddressFromCache($cacheItem);
    }
}