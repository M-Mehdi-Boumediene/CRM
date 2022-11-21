<?php

namespace App\Repository;

use App\Entity\TableauAbsences;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TableauAbsences>
 *
 * @method TableauAbsences|null find($id, $lockMode = null, $lockVersion = null)
 * @method TableauAbsences|null findOneBy(array $criteria, array $orderBy = null)
 * @method TableauAbsences[]    findAll()
 * @method TableauAbsences[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TableauAbsencesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TableauAbsences::class);
    }

    public function add(TableauAbsences $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(TableauAbsences $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }




    public function searchMot($value, $classe)
    { 
        if(!empty($value)){

            return $this->createQueryBuilder('u')
            ->leftJoin('u.intervenant', 'i')
            ->orWhere('i.nom LIKE :value')
            ->orWhere('i.prenom LIKE :value')
            ->orWhere('i.email LIKE :value')
            ->orWhere('i.telephone LIKE :value')
            ->setParameter('value', '%'.$value.'%')
  

            ->orderBy('u.id', 'ASC')
            ->getQuery()
            ->getResult()
            ;
        }
        if(!empty($classe)){
            return $this->createQueryBuilder('u')

            ->leftJoin('u.absences', 'a')
            ->leftJoin('a.classe', 'i')
            ->andWhere('i.id = :classe')

            ->setParameter('classe', $classe)

            ->getQuery()
            ->getResult()
        ;
        }

    }


//    /**
//     * @return TableauAbsences[] Returns an array of TableauAbsences objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('t.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?TableauAbsences
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
