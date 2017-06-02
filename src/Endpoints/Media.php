<?php

namespace Bencavens\Instagram\Endpoints;

class Media extends Endpoint
{
    public function search($username)
    {
        $this->requestPath = 'search';
        $this->params['q'] = $username;

        return $this;
    }
}