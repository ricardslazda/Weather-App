<?php

declare(strict_types=1);

namespace App\Service;

use Psr\Cache\CacheItemInterface;
use Symfony\Contracts\Cache\CacheInterface;

abstract class AbstractCacheService
{
    protected CacheInterface $cache;

    public function __construct(CacheInterface $cache)
    {
        $this->cache = $cache;
    }

    protected final function getFromCache(CacheItemInterface $cacheItem): mixed
    {
        return $cacheItem->get() ?? null;
    }

    protected final function getCacheItem(string $key): CacheItemInterface
    {
        return $this->cache->getItem($key);
    }

    protected final function isCached(CacheItemInterface $cacheItem): bool
    {
        return (bool)$this->getFromCache($cacheItem);
    }
}