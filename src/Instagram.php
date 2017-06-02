<?php

namespace Bencavens\Instagram;

use Bencavens\Instagram\Endpoints\User;
use Bencavens\Instagram\Endpoints\You;
use Bencavens\Instagram\Http\Client;
use Bencavens\Instagram\Http\CurlTransport;
use Bencavens\Instagram\Http\TransportContract;

final class Instagram
{
    /**
     * @var Client
     */
    private $client;

    private $client_id;
    private $client_secret;

    public function __construct(TransportContract $transport, $client_id, $client_secret, $access_token = null)
    {
        $this->client = new Client($transport, $client_secret, $access_token);
        $this->client_id = $client_id;
        $this->client_secret = $client_secret;
    }

    public static function init(string $client_id, string $client_secret, string $access_token = null)
    {
        return new self(new CurlTransport, $client_id, $client_secret, $access_token);
    }

    public function oauth(string $redirect_uri): OAuth
    {
        return new OAuth($this->client, $this->client_id, $this->client_secret, $redirect_uri);
    }

    public function you(): You
    {
        return new You($this->client);
    }

    public function user($userid = null): User
    {
        $user = new User($this->client);

        return ($userid) ? $user->find($userid) : $user;
    }
}