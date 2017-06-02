<?php

namespace Bencavens\Instagram\Http;

use GuzzleHttp\Client as GuzzleClient;

class GuzzleTransport implements TransportContract
{
    /**
     * @var Client
     */
    private $client;

    public function __construct(GuzzleClient $client)
    {
        $this->client = $client;
    }

    public function post($uri,array $data = [])
    {
        return $this->client->post($uri,[
            'form_params' => $data,
            'headers' => [
                'Accept: application/json',
            ],
        ]);
    }

    public function get($uri,array $data = [])
    {
        $response = $this->client->get($uri,$data);

        return $response->getBody()->getContents();
    }


}