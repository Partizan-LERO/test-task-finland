<?php


namespace Framework\Cache;


class InMemoryCache implements ICache
{
    public $cached = [];

    /**
     * @param  string  $key
     * @return null|mixed
     */
    public function get(string $key)
    {
        if (!array_key_exists($key, $this->cached)) return null;
        return $this->cached[$key];
    }

    public function set(string $key, $value)
    {
        $this->cached[$key] = $value;
    }

    public function delete(string $key)
    {
        if (array_key_exists($key, $this->cached)) unset($this->cached[$key]);
    }

    public function flush()
    {
        $this->cached = [];
    }
}
