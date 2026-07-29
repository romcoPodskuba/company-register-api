<?php

namespace App\Tests\Provider\CompanyRegister\Ares;

use App\Exception\CompanyRegister\RegisterResponseException;
use App\Provider\CompanyRegister\Ares\Mapper;
use PHPUnit\Framework\TestCase;

class MapperTest extends TestCase
{
    private Mapper $mapper;

    protected function setUp(): void
    {
        $this->mapper = new Mapper();
    }

    public function testMapCreatesCompanyFromAresResponse(): void
    {
        $companyFixtureData = $this->loadFixture('ares_company_response.json');

        $company = $this->mapper->map($companyFixtureData);

        $this->assertSame('01569651', $company->businessId);
        $this->assertSame('CZ01569651', $company->taxId);
        $this->assertSame('Superfaktura.cz, s.r.o.', $company->name);
        $this->assertSame('2904', $company->address->houseNumber);
        $this->assertSame('Šámalova', $company->address->street);
        $this->assertSame('Brno', $company->address->city);
        $this->assertSame('61500', $company->address->postalCode);
        $this->assertSame('Česká republika', $company->address->country);
        $this->assertEquals(new \DateTimeImmutable('2013-04-08'), $company->dateCreated);
    }

    public function testMapHandlesMissingTaxId(): void
    {
        $companyFixtureData = $this->loadFixture('ares_company_response.json');
        unset($companyFixtureData['dic']);

        $company = $this->mapper->map($companyFixtureData);

        $this->assertNull($company->taxId);
    }

    public function testMapThrowsRegisterResponseExceptionWhenDataIsIncomplete(): void
    {
        $this->expectException(RegisterResponseException::class);
        $this->expectExceptionMessage('Failed to parse register response.');

        $this->mapper->map(['ico' => '01569651']);
    }

    private function loadFixture(string $filename): array
    {
        $path = __DIR__ . '/fixtures/' . $filename;
        $json = file_get_contents($path);

        return json_decode($json, true, flags: JSON_THROW_ON_ERROR);
    }
}
