<?php

namespace App\Provider\CompanyRegister;

interface BusinessIdValidatorInterface
{
    public function validate(string $businessId): string;
}
