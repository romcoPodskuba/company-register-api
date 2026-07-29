<?php

namespace App\DTO\CompanyRegister;

final class Address
{
    public function __construct(
        public readonly string $houseNumber,
        public readonly string $street,
        public readonly string $city,
        public readonly string $postalCode,
        public readonly string $country
    ) {}
}
