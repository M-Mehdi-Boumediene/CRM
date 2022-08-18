<?php

namespace App\Repository;

use App\Entity\Modules;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Modules|null find($id, $lockMode = null, $lockVersion = null)
 * @method Modules|null findOneBy(array $criteria, array $orderBy = null)
 * @method Modules[]    findAll()
 * @method Modules[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ModulesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Modules::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Modules $entity, bool $flush = true): void
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
    public function remove(Modules $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function findModuleByClasse($classe,$module)
    {
        return $this->createQueryBuilder('u')
      
        ->innerJoin('u.classes', 'a')
            ->where('a.id = :classe')
            ->andWhere('u.id = :module')
            ->setParameter('module', $module)
            ->setParameter('classe', $classe)
            ->orderBy('u.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }
    public function findByClasse($id)
    {
        return $this->createQueryBuilder('u')
        ->innerJoin('u.classes', 'a')
        ->where('a.id = :classe')

        ->setParameter('classe', $id)

        ->orderBy('u.id', 'ASC')
        ->getQuery()
        ->getResult()
   

        ;
    }
    // /**
    //  * @return Modules[] Returns an array of Modules objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Modules
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
