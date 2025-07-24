<?php

namespace App\Services;

use App\Services\Service;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Psr7\Message;

class DeputadoApiService extends Service
{
    private readonly Client $client;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => 'https://dadosabertos.camara.leg.br/api/v2/',
            'timeout' => 15.0,
        ]);
    }

    public function all($filter = [])
    {
        try {
            $response = $this->client->get('deputados', [
                'query' => $filter
            ]);

            $body = $response->getBody()->getContents();

            return json_decode($body, true);
        } catch (ClientException $e) {
            return $this->exception($exception);
        } catch(Exception $exception) {
            return $this->exception($exception);
        }
    }
}
