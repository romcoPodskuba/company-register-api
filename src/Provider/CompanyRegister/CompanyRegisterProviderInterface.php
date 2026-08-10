<?php

namespace App\Provider\CompanyRegister;

use App\DTO\CompanyRegister\Company;

interface CompanyRegisterProviderInterface
{
    public function getCompany(string $businessId): Company;
}
