<?php

namespace App\Repository;

use App\Entity\Duplicates;
use App\Exception\DatabaseNotInitializedException;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Exception\TableNotFoundException;
use Doctrine\Persistence\ManagerRegistry;

class DuplicatesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Duplicates::class);
    }

    public function hasRows(): bool
    {
        try {
            return (bool) $this->count();
        } catch (TableNotFoundException $e) {
            throw new DatabaseNotInitializedException(
                'Database is not initialized. Please run make db-init or check the database.'
            );
        }
    }

    public function findAllDuplicatesValues(): array
    {
        // todo sql query

        return [];
    }
}
