<?php

declare(strict_types=1);

namespace App\Service;

use Psr\Cache\CacheItemInterface;
use Symfony\Component\Cache\Adapter\RedisAdapter;

abstract class AbstractCacheService
{
    protected RedisAdapter $cache;

    public function __construct()
    {
        $this->cache = new RedisAdapter(RedisAdapter::createConnection($_SERVER['REDIS_URL']));
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