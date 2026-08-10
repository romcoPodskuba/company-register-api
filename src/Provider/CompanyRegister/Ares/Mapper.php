<?php

namespace App\Provider\CompanyRegister\Ares;

use App\DTO\CompanyRegister\Address;
use App\DTO\CompanyRegister\Company;
use App\Exception\CompanyRegister\RegisterResponseException;

class Mapper
{
    public function map(array $data): Company
    {
        $addressData = $this->requireArray($data, 'sidlo');

        return new Company(
            businessId: $this->requireString($data, 'ico'),
            taxId: $this->optionalString($data, 'dic'),
            name: $this->requireString($data, 'obchodniJmeno'),
            address: new Address(
                houseNumber: $this->requireScalarAsString($addressData, 'cisloDomovni'),
                street: $this->optionalString($addressData, 'nazevUlice'),
                city: $this->requireString($addressData, 'nazevObce'),
                postalCode: $this->requireScalarAsString($addressData, 'psc'),
                country: $this->requireString($addressData, 'nazevStatu'),
            ),
            dateCreated: $this->requireDate($data, 'datumVzniku'),
        );
    }

    private function requireArray(array $data, string $key): array
    {
        if (!array_key_exists($key, $data) || !is_array($data[$key])) {
            throw new RegisterResponseException(sprintf('Missing or invalid field: %s', $key));
        }

        return $data[$key];
    }

    private function requireString(array $data, string $key): string
    {
        if (!array_key_exists($key, $data) || !is_string($data[$key]) || $data[$key] === '') {
            throw new RegisterResponseException(sprintf('Missing or invalid field: %s', $key));
        }

        return $data[$key];
    }

    private function optionalString(array $data, string $key): ?string
    {
        if (!array_key_exists($key, $data)) {
            return null;
        }

        if (!is_string($data[$key])) {
            throw new RegisterResponseException(sprintf('Invalid field type: %s', $key));
        }

        return $data[$key];
    }

    private function requireScalarAsString(array $data, string $key): string
    {
        if (!array_key_exists($key, $data)) {
            throw new RegisterResponseException(sprintf('Missing or invalid field: %s', $key));
        }

        if (!is_string($data[$key]) && !is_int($data[$key])) {
            throw new RegisterResponseException(sprintf('Invalid field type: %s', $key));
        }

        return (string) $data[$key];
    }

    private function requireDate(array $data, string $key): \DateTimeImmutable
    {
        $value = $this->requireString($data, $key);

        try {
            return new \DateTimeImmutable($value);
        } catch (\Exception $e) {
            throw new RegisterResponseException(
                sprintf('Invalid date format in field: %s', $key),
                0,
                $e
            );
        }
    }
}
