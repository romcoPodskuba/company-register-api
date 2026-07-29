<?php

namespace App\DTO\CompanyRegister;

final class Company
{
    public function __construct(
        public readonly string $businessId,
        public readonly string $name,
        public readonly AddressDto $address,
        public readonly \DateTimeImmutable $dateCreated,
        public readonly ?string $taxId = null
    ) {}
}
