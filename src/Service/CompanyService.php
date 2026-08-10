<?php

namespace App\Service;

use App\DTO\CompanyRegister\Company;
use App\Provider\CompanyRegister\CompanyRegisterProviderInterface;

class CompanyService
{
    public function __construct(
        private readonly CompanyRegisterProviderInterface $companyRegisterProvider
    ) {}

    public function getFromRegister(string $businessId): Company
    {
        return $this->companyRegisterProvider->getCompany($businessId);
    }
}
