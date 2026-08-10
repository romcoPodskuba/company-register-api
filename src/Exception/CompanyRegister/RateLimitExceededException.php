<?php

namespace App\Exception\CompanyRegister;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\WithHttpStatus;

#[WithHttpStatus(Response::HTTP_TOO_MANY_REQUESTS)]
class RateLimitExceededException extends \RuntimeException
{
    public function __construct(
        string $message = 'Rate limit exceeded.',
        private readonly ?int $retryAfterSeconds = null,
        ?\Throwable $previous = null,
    ) {
        parent::__construct($message, 0, $previous);
    }

    public function getRetryAfterSeconds(): ?int
    {
        return $this->retryAfterSeconds;
    }
}
