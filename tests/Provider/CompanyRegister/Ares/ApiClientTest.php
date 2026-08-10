<?php

namespace App\Tests\Provider\CompanyRegister\Ares;

use App\Exception\CompanyRegister\CompanyNotFoundException;
use App\Exception\CompanyRegister\RateLimitExceededException;
use App\Exception\CompanyRegister\RegisterResponseException;
use App\Exception\CompanyRegister\RegisterUnavailableException;
use App\Provider\CompanyRegister\Ares\ApiClient;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\Exception\TransportException;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;

class ApiClientTest extends TestCase
{
    public function testGetCompanyReturnsParsedJsonOnSuccess(): void
    {
        $fixture = file_get_contents(__DIR__ . '/fixtures/ares_company_response.json');

        $httpClient = new MockHttpClient([
            new MockResponse($fixture, ['http_code' => 200]),
        ]);

        $apiClient = new ApiClient($httpClient);
        $result = $apiClient->getCompany('01569651');

        $this->assertSame('01569651', $result['ico']);
    }

    public function testGetCompanyThrowsCompanyNotFoundExceptionOn404(): void
    {
        $httpClient = new MockHttpClient([
            new MockResponse('', ['http_code' => 404]),
        ]);
        $apiClient = new ApiClient($httpClient);

        $this->expectException(CompanyNotFoundException::class);
        $this->expectExceptionMessage('Company not found.');

        $apiClient->getCompany('01569651');
    }

    public function testGetCompanyThrowsRateLimitExceededExceptionOn429(): void
    {
        $httpClient = new MockHttpClient([
            new MockResponse('', [
                'http_code' => 429,
                'response_headers' => ['retry-after' => ['60']],
            ]),
        ]);
        $apiClient = new ApiClient($httpClient);

        try {
            $apiClient->getCompany('01569651');
            $this->fail('Expected RateLimitExceededException to be thrown.');
        } catch (RateLimitExceededException $e) {
            $this->assertSame('Rate limit exceeded.', $e->getMessage());
            $this->assertSame(60, $e->getRetryAfterSeconds());
        }
    }

    public function testGetCompanyThrowsRegisterUnavailableExceptionOnTransportError(): void
    {
        $httpClient = new MockHttpClient(static function (): never {
            throw new TransportException('Connection timeout.');
        });
        $apiClient = new ApiClient($httpClient);

        $this->expectException(RegisterUnavailableException::class);
        $this->expectExceptionMessage('Register service is unavailable.');

        $apiClient->getCompany('01569651');
    }

    public function testGetCompanyThrowsRegisterResponseExceptionOnInvalidJson(): void
    {
        $httpClient = new MockHttpClient([
            new MockResponse('not-json', ['http_code' => 200]),
        ]);
        $apiClient = new ApiClient($httpClient);

        $this->expectException(RegisterResponseException::class);
        $this->expectExceptionMessage('Failed to parse register response.');

        $apiClient->getCompany('01569651');
    }
}
