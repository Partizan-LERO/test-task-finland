<?php

namespace App\Service;

use App\Exceptions\InvalidApiResponseException;
use Framework\Cache\ICache;
use Framework\EnvParser\Env;
use Framework\Exceptions\FileNotFoundException;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Message\ResponseInterface;

class ApiService
{
    private Client $client;
    private ICache $cache;

    /**
     * ApiService constructor.
     * @param  Client  $client
     * @param  ICache  $cache
     */
    public function __construct(Client $client, ICache $cache)
    {
        $this->client = $client;
        $this->cache = $cache;
    }


    /**
     * @return string
     * @throws FileNotFoundException
     * @throws GuzzleException
     * @throws InvalidApiResponseException
     */
    private function login(): string
    {
        $response = $this->client->post('/assignment/register', [
            'form_params' => [
                'name' => Env::get('sm_api_name'),
                'email' => Env::get('sm_api_email'),
                'client_id' => Env::get('sm_api_client_id'),
            ]
        ]);

        $this->validateResponse($response);
        $data = json_decode($response->getBody()->getContents(), true, 512, JSON_THROW_ON_ERROR);

        if (!array_key_exists('data', $data) || !array_key_exists('sl_token', $data['data'])) {
            throw new InvalidApiResponseException();
        }

        $token = $data['data']['sl_token'];

        $this->cache->set('sm_api_user_token', $token);
        return $token;
    }


    /**
     * @param  int $page
     * @return array
     * @throws FileNotFoundException
     * @throws GuzzleException
     * @throws InvalidApiResponseException
     * @throws ClientExceptionInterface
     */
    public function getData(int $page): array
    {
        $token = $this->cache->get('sm_api_user_token');

        if (!$token) {
            $token = $this->login();
            $this->cache->set('sm_api_user_token', $token);
        }

        $response = $this->client->get('/assignment/posts', [
            'query' => [
                'sl_token' => $token,
                'page' => $page
            ]
        ]);

        $this->validateResponse($response);
        $data = json_decode($response->getBody()->getContents(), true, 512, JSON_THROW_ON_ERROR);

        if (!array_key_exists('data', $data) || !array_key_exists('posts', $data['data'])) {
            return [];
        }

        return $data['data'];
    }

    /**
     * @param  ResponseInterface  $response
     * @return bool
     * @throws InvalidApiResponseException
     */
    private function validateResponse(ResponseInterface $response): bool
    {
        if ($response->getStatusCode() !== 200) {
            throw new InvalidApiResponseException('Response code does not equals to 200');
        }

        return true;
    }
}
