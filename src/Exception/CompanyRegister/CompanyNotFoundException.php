<?php

namespace App\Exception\CompanyRegister;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\WithHttpStatus;

#[WithHttpStatus(Response::HTTP_NOT_FOUND)]
class CompanyNotFoundException extends \RuntimeException {}
