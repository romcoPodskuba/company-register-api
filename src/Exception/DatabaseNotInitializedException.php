<?php

namespace App\Exception;

use Symfony\Component\HttpKernel\Attribute\WithHttpStatus;

#[WithHttpStatus(500)]
class DatabaseNotInitializedException extends \RuntimeException {}
