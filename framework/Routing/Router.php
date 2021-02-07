<?php

namespace Framework\Routing;

use App\Provider\AppServiceProvider;
use ArgumentCountError;
use BadMethodCallException;
use Framework\Exceptions\CannotInstantiateClassException;
use Framework\Exceptions\UnknownCacheDriverException;
use Framework\Exceptions\UnknownDatabaseDriverException;
use Framework\Http\Response\ExceptionResponse;
use Framework\Http\Response\NotFoundResponse;

class Router
{
    private array $routes;

    /**
     * @param  array  $routes
     * @return void
     */
    public function setRoutes(array $routes): void
    {
        $this->routes = $routes;
    }

    /**
     * @return array
     */
    public function getRoutes(): array
    {
        return $this->routes;
    }

    /**
     * @param  string  $uri
     * @throws UnknownCacheDriverException
     * @throws UnknownDatabaseDriverException
     */
    public function handle(string $uri): void
    {
        $provider = new AppServiceProvider();
        $services = $provider->register();

        foreach ($this->routes as $path => $route) {
            if ($path === $uri) {
                try {
                    $instance = $this->instantiate($services, $route['class']);
                } catch (CannotInstantiateClassException $e) {
                    ExceptionResponse::send($e->getMessage());
                    return;
                }

                $method = $route['method'];

                if (!method_exists($instance, $method)) {
                    throw new BadMethodCallException(
                        'Wrong method name "' .$route['method'] . '" in the routes config'
                    );
                }
                echo $instance->$method();
                return;
            }
        }

         NotFoundResponse::send();
    }

    /**
     * @param  array  $services
     * @param  string  $class
     * @return mixed
     * @throws CannotInstantiateClassException
     */
    private function instantiate(array $services, string $class)
    {
        if (!array_key_exists($class, $services)) {
            throw new CannotInstantiateClassException('Cannot instantiate class "' . $class .
                '". which is not presented in AppServiceProvider');
        }

        try {
            $instance = $services[$class]();
        } catch (ArgumentCountError $e) {
            throw new CannotInstantiateClassException('Cannot instantiate class "' . $class .
                '". The reason: ' . $e->getMessage());
        }

        return $instance;
    }

}
