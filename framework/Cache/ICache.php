<?php

namespace Framework\Cache;

interface ICache
{
    public function get(string $key);
    public function set(string $key, $value);
    public function delete(string $key);
    public function flush();
}
