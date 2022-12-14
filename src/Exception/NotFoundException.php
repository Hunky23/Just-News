<?php

namespace App\Exception;

class NotFoundException extends BaseApiException
{
    public null|string|array $reason = 'Item not found';
    public ?int $statusCode = 404;
    public ?int $errorCode  = 404;
}