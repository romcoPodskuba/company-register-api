<?php

namespace App\Provider\CompanyRegister\Ares;

use App\DTO\CompanyRegister\Company;
use App\Provider\CompanyRegister\CompanyRegisterProviderInterface;

class CompanyRegisterProvider implements CompanyRegisterProviderInterface
{
    public function __construct(
        private readonly ApiClient $apiClient,
        private readonly Mapper $mapper
    ) {}

    public function getCompany(string $businessId): Company
    {
        $companyFromRegister = $this->apiClient->getCompany($businessId);

        return $this->mapper->map($companyFromRegister);
    }
}
