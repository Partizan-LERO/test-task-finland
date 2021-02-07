<?php

namespace Framework\EnvParser;

use Framework\Exceptions\FileNotFoundException;

/**
 * Class Env
 * @package Framework\EnvParser
 */
class Env
{
    const FILE = '.env';

    /**
     * @param  string  $needle
     * @return string|null
     * @throws FileNotFoundException
     */
    public static function get(string $needle): ?string
    {
        if (!file_exists(self::FILE)) {
            throw new FileNotFoundException('.env file not found');
        }

        return parse_ini_file(self::FILE)[$needle] ?? null;
    }
}
