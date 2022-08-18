<?php

namespace App\Repository;

use App\Entity\Blocs;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Blocs|null find($id, $lockMode = null, $lockVersion = null)
 * @method Blocs|null findOneBy(array $criteria, array $orderBy = null)
 * @method Blocs[]    findAll()
 * @method Blocs[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BlocsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Blocs::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Blocs $entity, bool $flush = true): void
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
    public function remove(Blocs $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }


    public function findByClasse($classe)
    {
        return $this->createQueryBuilder('u')
      
        ->innerJoin('u.classes', 'a')
            ->where('a.id = :classe')         
            ->setParameter('classe', $classe)
            ->orderBy('u.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    public function findByblocByClasse($classe)
    {
        return $this->createQueryBuilder('u')
      
        ->innerJoin('u.Classe', 'a')
            ->where('a.id = :classe')         
            ->setParameter('classe', $classe)
            ->orderBy('u.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }


    public function findblocByClasse($classe,$bloc)
    {
        return $this->createQueryBuilder('u')
      
        ->innerJoin('u.classes', 'a')
            ->where('a.id = :classe')
            ->andWhere('u.id = :bloc')
            ->setParameter('bloc', $bloc)
            ->setParameter('classe', $classe)
            ->orderBy('u.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }
    // /**
    //  * @return Blocs[] Returns an array of Blocs objects
    //  */
    /*

    
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Blocs
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
