<?php


namespace Framework\Cache;


use Framework\Config;
use Framework\Exceptions\UnknownCacheDriverException;

class Cache implements ICache
{
    private ICache $storage;

    /**
     * ProcessedDataStorage constructor.
     * @throws UnknownCacheDriverException
     */
    public function __construct()
    {
        switch (Config::get('cache.driver')) {
            case 'file':
               $this->storage = new FileCache();
               break;
            default:
                throw new UnknownCacheDriverException('Unknown cache driver exception');
        }
    }

    /**
     * @param $key
     * @param $value
     */
    public function set($key, $value)
    {
        $this->storage->set($key, $value);
    }

    /**
     * @param $key
     * @return mixed
     */
    public function get($key)
    {
        return $this->storage->get($key);
    }

    public function delete(string $key)
    {
        $this->storage->delete($key);
    }

    public function flush()
    {
        $this->storage->flush();
    }
}
