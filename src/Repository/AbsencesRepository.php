<?php

namespace App\Repository;

use App\Entity\Absences;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Absences|null find($id, $lockMode = null, $lockVersion = null)
 * @method Absences|null findOneBy(array $criteria, array $orderBy = null)
 * @method Absences[]    findAll()
 * @method Absences[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AbsencesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Absences::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Absences $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(Absences $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }


    public function findByUser($user)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.user = :val')
            ->setParameter('val', $user)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
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


            ->leftJoin('u.classe', 'i')
            ->andWhere('i.id = :classe')

            ->setParameter('classe', $classe)

            ->getQuery()
            ->getResult()
        ;
        }

    }

    /*
    public function findOneBySomeField($value): ?Absences
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
