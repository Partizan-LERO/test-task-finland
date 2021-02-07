<?php

namespace Framework;

/**
 * Class Config
 * @package Framework
 */
class Config {
    /**
     * @param  string $key
     * @return mixed|null
     */
    public static function get(string $key)
    {
        $config = include './config/config.php';

        $keys = explode('.', $key);

        return self::getValue($config, $keys);
    }

    /**
     * @param $config
     * @param $keys
     * @param  null  $value
     * @return null
     */
    private static function getValue($config, $keys, $value = null)
    {
        foreach ($keys as $key) {
            if (isset($config[$key])) {
                return self::getValue($config[$key], array_slice($keys, 1), $config[$key]);
            }
        }

        return $value;
    }


}
