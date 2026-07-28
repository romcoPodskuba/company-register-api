<?php

namespace App\Service;

use App\Exception\DatabaseNotInitializedException;
use App\Repository\DuplicatesRepository;

class DuplicatesService
{
    public function __construct(
        private readonly DuplicatesRepository $duplicatesRepository
    ) {}

    public function list(): array
    {
        if (!$this->duplicatesRepository->hasRows()) {
            // I would not use this exception in a real app, because an empty result is a valid output.
            throw new DatabaseNotInitializedException(
                'Database table duplicates is empty. Please run make db-init or check the database.'
            );
        }

        return $this->duplicatesRepository->findAllDuplicatesValueRows();
    }
}
