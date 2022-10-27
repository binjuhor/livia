<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Collection;
use Psr\Http\Message\ResponseInterface;

class SamirService
{
    protected ?Client $guzzleClient = null;

    protected string $baseUrl = 'https://samir.chillbits.com/';

    protected string $token = '';

    public function __construct()
    {
        $this->token = config('services.samir.key');
    }

    public function getBaseUrl(): string
    {
        return $this->baseUrl;
    }

    /**
     * @throws GuzzleException
     */
    public function sendRequest(string $method, string $endpoint, $requestOptions = []): ResponseInterface
    {
        return $this->getHttpClient()->request(
            $method,
            $endpoint,
            $requestOptions
        );
    }

    protected function getHttpClient(): Client
    {
        if (!$this->guzzleClient) {
            $this->guzzleClient = $this->createGuzzleClient(
                $this->getDefaultRequestOptions()
            );
        }

        return $this->guzzleClient;
    }

    protected function getDefaultRequestOptions(): array
    {
        return [
            'headers' => [
                'Accept' => 'application/json'
            ]
        ];
    }

    protected function createGuzzleClient($options): Client
    {
        return new Client(
            array_merge(
                [
                    'base_uri' => $this->getBaseUrl(),
                    'timeout' => 30,
                    'cookies' => false,
                    'allow_redirects' => true,
                    'http_errors' => true,
                ],
                $options
            )
        );
    }

    /**
     * @throws GuzzleException
     */
    public function getBalances(): Collection
    {
        $response = $this->sendRequest(
            'POST',
            '/api/balances',
            [
                'json' => [
                    'secret' => $this->token
                ]
            ]
        );
        return collect(json_decode((string)$response->getBody(), true));
    }
}