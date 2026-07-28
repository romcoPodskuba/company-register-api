<?php

namespace App\Exception;

use Symfony\Component\HttpKernel\Attribute\WithHttpStatus;

#[WithHttpStatus(404)]
class DatabaseNotInitializedException extends \RuntimeException {}
