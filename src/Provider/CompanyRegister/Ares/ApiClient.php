<?php

namespace App\Provider\CompanyRegister\Ares;

use App\Exception\CompanyRegister\CompanyNotFoundException;
use App\Exception\CompanyRegister\ForbiddenException;
use App\Exception\CompanyRegister\InvalidBusinessIdException;
use App\Exception\CompanyRegister\RateLimitExceededException;
use App\Exception\CompanyRegister\RegisterResponseException;
use App\Exception\CompanyRegister\RegisterUnavailableException;
use App\Exception\CompanyRegister\UnauthorizedException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class ApiClient
{
    // Docs available at: https://ares.gov.cz/swagger-ui/#/ekonomicke-subjekty/vratEkonomickySubjekt

    private const BASE_URL = 'https://ares.gov.cz/ekonomicke-subjekty-v-be/rest/ekonomicke-subjekty';

    public function __construct(
        private readonly HttpClientInterface $httpClient
    ) {}

    public function getCompany(string $businessId): array
    {
        try {
            $response = $this->httpClient->request('GET', self::BASE_URL . '/' . $businessId);
            $statusCode = $response->getStatusCode();
        } catch (TransportExceptionInterface $e) {
            throw new RegisterUnavailableException('Register service is unavailable.', 0, $e);
        }

        if ($statusCode === Response::HTTP_BAD_REQUEST) {
            throw new InvalidBusinessIdException('Invalid business ID.');
        }

        if ($statusCode === Response::HTTP_UNAUTHORIZED) {
            throw new UnauthorizedException('Unauthorized access.');
        }

        if ($statusCode === Response::HTTP_FORBIDDEN) {
            throw new ForbiddenException('Access denied.');
        }

        if ($statusCode === Response::HTTP_NOT_FOUND) {
            throw new CompanyNotFoundException('Company not found.');
        }

        if ($statusCode === Response::HTTP_TOO_MANY_REQUESTS) {
            throw new RateLimitExceededException(
                'Rate limit exceeded.',
                $this->extractRetryAfterSeconds($response)
            );
        }

        if ($statusCode >= Response::HTTP_INTERNAL_SERVER_ERROR) {
            throw new RegisterUnavailableException('Register service is unavailable.');
        }

        if ($statusCode !== Response::HTTP_OK) {
            throw new RegisterResponseException(
                sprintf('Unexpected response from register service (HTTP %d).', $statusCode)
            );
        }

        try {
            return $response->toArray();
        } catch (DecodingExceptionInterface $e) {
            throw new RegisterResponseException('Failed to parse register response.', 0, $e);
        }
    }

    private function extractRetryAfterSeconds(ResponseInterface $response): ?int
    {
        $headers = $response->getHeaders(false);
        $retryAfter = $headers['retry-after'][0] ?? null;

        if ($retryAfter === null || !ctype_digit((string) $retryAfter)) {
            return null;
        }

        return (int) $retryAfter;
    }
}
