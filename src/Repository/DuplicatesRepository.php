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

    public function findAllDuplicatesValueRows(): array
    {
        // Odpoveď k otázkam na zamyslenie zo zadania:
        // Pri tabuľke s väčším počtom riadkov by som pridal index na stĺpec value,
        // pretože podľa neho sa zgrupuje aj filtruje. Vo väčšine prípadov by mal index
        // stačiť. Ďalšiu optimalizáciu by som zvažoval až v prípade, že by to ukázalo
        // EXPLAIN alebo ďalšia analýza problému.

        $q = "SELECT id, value
              FROM duplicates
              WHERE value IN (
                  SELECT value
                  FROM duplicates
                  GROUP BY value
                  HAVING COUNT(*) > 1
              )";
        $stmt = $this->getEntityManager()->getConnection()->executeQuery($q);

        return $stmt->fetchAllAssociative();
    }
}
