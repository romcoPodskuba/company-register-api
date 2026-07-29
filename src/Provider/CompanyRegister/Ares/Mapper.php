<?php

namespace App\Provider\CompanyRegister\Ares;

use App\DTO\CompanyRegister\Address;
use App\DTO\CompanyRegister\Company;
use App\Exception\CompanyRegister\RegisterResponseException;

class Mapper
{
    public function map(array $data): Company
    {
        try {
            return new Company(
                businessId: $data['ico'],
                taxId: $data['dic'] ?? null,
                name: $data['obchodniJmeno'],
                address: new Address(
                    houseNumber: (string) $data['sidlo']['cisloDomovni'],
                    street: $data['sidlo']['nazevUlice'] ?? null,
                    city: $data['sidlo']['nazevObce'],
                    postalCode: (string) $data['sidlo']['psc'],
                    country: $data['sidlo']['nazevStatu'],
                ),
                dateCreated: new \DateTimeImmutable($data['datumVzniku'])
            );
        } catch (\Throwable $e) {
            throw new RegisterResponseException('Failed to parse register response.', 0, $e);
        }
    }
}
