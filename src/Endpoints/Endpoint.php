<?php

namespace Bencavens\Instagram\Endpoints;

use Bencavens\Instagram\Http\Client;
use Bencavens\Instagram\Http\Response;
use Bencavens\Instagram\Instagram;
use stdClass;

abstract class Endpoint
{
    protected $requiresAuth = true;
    protected $requestEndpoint = '/';
    protected $requestPath;
    protected $params = [];

    /**
     * @var Instagram
     */
    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function limit($limit)
    {
        // TODO: This seems to be giving some problems in sandbox.
        // If we search for 'be' we get nothing with param 2 and results with param 4??
        // Also: check this is only set on views that allow it.
        $this->params['count'] = (int)$limit;

        return $this;
    }

    /**
     * Depending on request this returns either an array or stdClass
     *
     * @return array | stdClass
     */
    public function get()
    {
        return $this->raw()->getData();
    }

    /**
     * TODO: what if return is NULL?
     *
     * @return stdClass
     */
    public function first()
    {
        return $this->raw()->getFirst();
    }

    public function raw(): Response
    {
        $endpoint = $this->requestEndpoint.'/'.$this->requestPath;
        $this->requestPath = null;

        return $this->client->get($endpoint, $this->params, $this->requiresAuth);
    }
}