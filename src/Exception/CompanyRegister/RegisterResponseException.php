<?php

namespace App\Exception\CompanyRegister;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\WithHttpStatus;

#[WithHttpStatus(Response::HTTP_BAD_GATEWAY)]
class RegisterResponseException extends \RuntimeException {}
