<?php

namespace Guilty\HubspotConnector\services\Guzzle;

use Kevinrob\GuzzleCache\CacheEntry;
use Kevinrob\GuzzleCache\Storage\CacheStorageInterface;

class CraftCacheStorage implements CacheStorageInterface
{

    protected $cache;

    /**
     * @param Cache $cache
     */
    public function __construct()
    {
        $this->cache = \Craft::$app->cache;
    }

    /**
     * {@inheritdoc}
     */
    public function fetch($key)
    {
        $data = $this->cache->get($key);
        return $data === false ? null : unserialize($this->cache->get($key));
    }

    /**
     * {@inheritdoc}
     */
    public function save($key, CacheEntry $data)
    {
        $this->cache->set($key, serialize($data));
    }

    /**
     * {@inheritdoc}
     */
    public function delete($key)
    {
        return $this->cache->delete($key);
    }
}
