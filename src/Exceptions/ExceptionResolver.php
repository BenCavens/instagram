<?php

namespace Bencavens\Instagram\Exceptions;

use stdClass;

class ExceptionResolver{

    /**
     * Resolve an instagram error message as a domain exception
     *
     * @param $type
     * @param $code
     * @param $message
     * @param null $requestInfo
     * @throws InstagramException
     */
    public static function resolve($type, $code, $message, $requestInfo = null)
    {
        $class = '\\Bencavens\\Instagram\\Exceptions\\'.$type;

        if(class_exists($class))
        {
            throw new $class($requestInfo.$message, $code);
        }

        throw new InstagramException('['.$type.'] '.$requestInfo.$message, 500);
    }

    /**
     * @param stdClass $raw
     * @param null $requestInfo
     * @throws InstagramException
     */
    public static function resolveFromRawResponse(stdClass $raw, $requestInfo = null)
    {
        if (isset($raw->meta) && isset($raw->meta->error_type)) {
            self::resolve(
                $raw->meta->error_type,
                $raw->meta->code,
                $raw->meta->error_message,
                $requestInfo
            );
        }
        elseif (isset($raw->error_type)) {
            self::resolve(
                $raw->error_type,
                $raw->code,
                $raw->error_message,
                $requestInfo
            );
        }
    }

}