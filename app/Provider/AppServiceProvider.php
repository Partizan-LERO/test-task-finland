<?php

namespace App\Provider;

use App\Http\Controller\PostReportController;
use App\Repository\SqlPostRepository;
use App\Service\ApiService;
use Framework\Cache\Cache;
use Framework\Database\DbDriver;
use Framework\EnvParser\Env;
use Framework\Exceptions\CannotInstantiateClassException;
use Framework\Exceptions\FileNotFoundException;
use Framework\Exceptions\UnknownCacheDriverException;
use Framework\Exceptions\UnknownDatabaseDriverException;
use GuzzleHttp\Client;
use PDO;

class AppServiceProvider
{
    /**
     * @return array
     * @throws FileNotFoundException
     * @throws UnknownCacheDriverException
     * @throws UnknownDatabaseDriverException
     */
    public function register()
    {
        $pdo = $this->getPdo();
        $cache = new Cache();

        return [
            PostReportController::class => function() use($pdo, $cache) {
                return new PostReportController(new SqlPostRepository($pdo), $cache);
            },
            SqlPostRepository::class => function() use($pdo) {
                return new SqlPostRepository($pdo);
            },
            ApiService::class => function() use($cache) {
                $client = new Client(['base_uri' => Env::get('sm_api_base_uri'), 'verify' => false]);
                return new ApiService($client, $cache);
            }
        ];
    }

    /**
     * @param  string  $class
     * @return mixed
     * @throws CannotInstantiateClassException
     * @throws FileNotFoundException
     * @throws UnknownCacheDriverException
     * @throws UnknownDatabaseDriverException
     */
    public function get(string $class)
    {
        $services = $this->register();

        foreach ($services as $name => $service) {
            if ($name === $class) {
                return $service();
            }
        }

        throw new CannotInstantiateClassException('This service is not registered in the provider');
    }

    /**
     * @return PDO
     * @throws UnknownDatabaseDriverException
     * @throws FileNotFoundException
     */
    private function getPdo()
    {
        $driver = new DbDriver(Env::get('db.driver'));

        return $driver->conn();
    }
}
