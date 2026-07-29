<?php

namespace App\Exception\CompanyRegister;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\WithHttpStatus;

#[WithHttpStatus(Response::HTTP_INTERNAL_SERVER_ERROR)]
class UnexcepectedException extends \RuntimeException {}
