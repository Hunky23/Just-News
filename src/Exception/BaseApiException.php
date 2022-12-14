<?php

namespace App\Exception;

use Throwable;
use Symfony\Component\HttpKernel\Exception\HttpException;

class BaseApiException extends HttpException
{
    public ?int $statusCode = null;
    public ?int $errorCode = null;
    public ?Throwable $previous = null;
    public null|string|array $payload;

    public function __construct(null|string|array $payload = null, Throwable $previous = null, array $headers = [])
    {
        parent::__construct($this->statusCode, $this->message, $previous, $headers, $this->errorCode);

        $this->payload = $payload;
    }
}