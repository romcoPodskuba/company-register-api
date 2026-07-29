<?php

namespace App\Provider\CompanyRegister\Ares;

use App\Exception\CompanyRegister\CompanyNotFoundException;
use App\Exception\CompanyRegister\ForbiddenException;
use App\Exception\CompanyRegister\InvalidBusinessIdException;
use App\Exception\CompanyRegister\RegisterResponseException;
use App\Exception\CompanyRegister\RegisterUnavailableException;
use App\Exception\CompanyRegister\UnauthorizedException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ApiClient
{
    private const BASE_URL = 'https://ares.gov.cz/ekonomicke-subjekty-v-be/rest/ekonomicke-subjekty';

    public function __construct(
        private readonly HttpClientInterface $httpClient
    ) {}

    public function getCompany(string $businessId): array
    {
        try {
            $response = $this->httpClient->request('GET', self::BASE_URL . '/' . $businessId);
            $statusCode = $response->getStatusCode();
        } catch (\Throwable $e) {
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

        if ($statusCode !== Response::HTTP_OK) {
            throw new RegisterUnavailableException('Register service is unavailable.');
        }

        try {
            return $response->toArray();
        } catch (\Throwable $e) {
            throw new RegisterResponseException('Failed to parse register response.', 0, $e);
        }
    }
}
