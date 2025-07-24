<?php

namespace App\Services;

use App\Services\Service;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Psr7\Message;
use Illuminate\Support\Facades\Log;

class DeputadoApiService extends Service
{
    private readonly Client $client;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => 'https://dadosabertos.camara.leg.br/api/v2/',
            'timeout' => 15.0,
            'connect_timeout' => 5.0
        ]);
    }

    public function all($filter = [])
    {
        try {
            // $response = $this->client->get('deputados', [
            //     'query' => $filter
            // ]);

            $response = retry(3, function () use ($filter) {
                return $this->client->get('deputados', [
                    'query' => $filter,
                ]);
            }, 300); // 3 tentativas, 300ms intervalo

            $body = $response->getBody()->getContents();

            return json_decode($body, true);
        } catch (ClientException $exception) {
            return $this->exception($exception);
        } catch(ConnectException $exception) {
            Log::warning('Erro de conexão com API da Câmara: ' . $e->getMessage());

            return $this->exception($exception);
        } catch(Exception $exception) {
            return $this->exception($exception);
        }
    }
}
