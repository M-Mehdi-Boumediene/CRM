<?php

namespace App\Repository;

use App\Entity\TableNotes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TableNotes>
 *
 * @method TableNotes|null find($id, $lockMode = null, $lockVersion = null)
 * @method TableNotes|null findOneBy(array $criteria, array $orderBy = null)
 * @method TableNotes[]    findAll()
 * @method TableNotes[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TableNotesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TableNotes::class);
    }

    public function add(TableNotes $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(TableNotes $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }



   
}
