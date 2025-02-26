<?php

declare(strict_types=1);

namespace Whirlwind\Infrastructure\Http\Exception;

class ServerErrorHttpException extends HttpException
{
    public function __construct($message = null, $code = 0, \Exception $previous = null)
    {
        parent::__construct(500, $message, $code, $previous);
    }
}
