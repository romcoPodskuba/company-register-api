<?php

namespace App\EventListener;

use App\Exception\CompanyRegister\RateLimitExceededException;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;

#[AsEventListener(event: KernelEvents::EXCEPTION)]
final class RateLimitExceededExceptionListener
{
    public function __invoke(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        if (!$exception instanceof RateLimitExceededException) {
            return;
        }

        $response = new JsonResponse(
            [
                'status' => JsonResponse::HTTP_TOO_MANY_REQUESTS,
                'detail' => $exception->getMessage(),
            ],
            JsonResponse::HTTP_TOO_MANY_REQUESTS
        );

        if ($exception->getRetryAfterSeconds() !== null) {
            $response->headers->set('Retry-After', (string) $exception->getRetryAfterSeconds());
        }

        $event->setResponse($response);
    }
}
