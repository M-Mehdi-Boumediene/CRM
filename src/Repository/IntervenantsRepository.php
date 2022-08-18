<?php

namespace App\Repository;

use App\Entity\Intervenants;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Intervenants|null find($id, $lockMode = null, $lockVersion = null)
 * @method Intervenants|null findOneBy(array $criteria, array $orderBy = null)
 * @method Intervenants[]    findAll()
 * @method Intervenants[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class IntervenantsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Intervenants::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Intervenants $entity, bool $flush = true): void
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
    public function remove(Intervenants $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    // /**
    //  * @return Intervenants[] Returns an array of Intervenants objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('i.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */


    public function findByClasse($classe)
    {
        return $this->createQueryBuilder('e')
        ->where('e.classes = :val')
        ->setParameter('val', $classe)
   
  
        ->getQuery()
        ->getResult()
        ;
    }

}
