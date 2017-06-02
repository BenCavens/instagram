<?php

namespace Bencavens\Instagram\Endpoints;

use Bencavens\Instagram\Exceptions\InstagramException;
use Bencavens\Instagram\Http\Response;

abstract class UserEndpoint extends Endpoint
{
    protected $userid;
    protected $requestEndpoint = '/users';

    public function media()
    {
        $this->requestPath = 'media/recent';

        return $this;
    }

    public function raw(): Response
    {
        // Me - userid are required for all except the search call
        if('search' !== $this->requestPath)
        {
            if(!$this->userid)
            {
                throw new InstagramException('Request ['.$this->requestPath.'] requires a subject. None given. This can be either a userid or self.');
            }

            $this->requestPath = $this->userid.'/'.$this->requestPath;
        }

        return parent::raw();
    }
}