<?php

namespace App\Service;

use App\Repository\DuplicatesRepository;

class DuplicatesService
{
    public function __construct(
        private readonly DuplicatesRepository $duplicatesRepository
    ) {}

    public function list(): array
    {
        // todo check if the duplicates are already in the database

        return $this->duplicatesRepository->findAllDuplicatesValues();
    }
}
