<?php

namespace Bencavens\Instagram\Endpoints;

class You extends UserEndpoint
{
    protected $userid = 'self';

    public function likes(): self
    {
        $this->requestPath = 'media/liked';

        return $this;
    }

    public function follows(): self
    {
        $this->requestPath = 'follows';

        return $this;
    }

    public function followedBy(): self
    {
        $this->requestPath = 'followed-by';

        return $this;
    }

    public function requestedBy(): self
    {
        $this->requestPath = 'requested-by';

        return $this;
    }
}