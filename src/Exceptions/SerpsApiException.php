<?php

namespace BrightOak\Serps\Exceptions;

use Exception;
use GuzzleHttp\Psr7\Response;

class SerpsException extends Exception
{
    public static function error(Response $response)
    {
        return new static("Response Code: " . $response->getStatusCode() . ". " . $response->getReasonPhrase());
    }

}
