<?php


namespace Framework\Database;

use Framework\Config;
use Framework\Exceptions\UnknownDatabaseDriverException;
use PDO;

class DbDriver
{
    private string $driver;

    public function __construct(string $driver)
    {
        $this->driver = $driver;
    }

    /**
     * @return PDO
     * @throws UnknownDatabaseDriverException
     */
    public function conn(): PDO
    {
        if ($this->driver === 'pdo_sqlite') {
            return new PDO('sqlite:'.Config::get('db.path'));
        }

        throw new UnknownDatabaseDriverException('Unknown database driver exception');
    }
}
