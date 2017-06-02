<?php

namespace Bencavens\Instagram\Http;

use stdClass;

class Response
{
    /**
     * @var stdClass
     */
    private $raw;

    public function __construct(stdClass $raw)
    {
        $this->raw = $raw;
    }

    public function getRaw(): stdClass
    {
        return $this->raw;
    }

    public function getData()
    {
        return $this->raw->data;
    }

    /**
     * @return stdClass | null
     */
    public function getFirst()
    {
        $rows = $this->getData();

        if(is_array($rows))
        {
            return !empty($rows) ? reset($rows) : null;
        }

        return $rows;
    }

    public function getStatusCode(): int
    {
        return $this->raw->meta->code;
    }
}