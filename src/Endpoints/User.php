<?php

namespace Bencavens\Instagram\Endpoints;

class User extends UserEndpoint
{
    public function find(int $userid): self
    {
        $this->userid = $userid;

        return $this;
    }

    public function search(string $username): self
    {
        $this->requestPath = 'search';
        $this->params['q'] = $username;

        return $this;
    }
}