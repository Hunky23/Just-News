<?php

namespace App\Exception;

class UnknownServerErrorException extends BaseApiException
{
    public null|string|array $reason = 'Unknown error';
    public ?int $statusCode = 520;
    public ?int $errorCode  = 520;
}