<?php

namespace App\Repository;

use App\Entity\Messages;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Messages|null find($id, $lockMode = null, $lockVersion = null)
 * @method Messages|null findOneBy(array $criteria, array $orderBy = null)
 * @method Messages[]    findAll()
 * @method Messages[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MessagesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Messages::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Messages $entity, bool $flush = true): void
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
    public function remove(Messages $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    // /**
    //  * @return Messages[] Returns an array of Messages objects
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
    public function findOneBySomeField($value): ?Messages
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */


    public function findBybrouillon($user)
    {
        return $this->createQueryBuilder('u')
            

        ->andWhere('u.brouillon = :brouillon')
        ->andWhere('u.sender = :user')
   


        ->setParameter('brouillon', 1)
        ->setParameter('user', $user)
        ->orderBy('u.id', 'DESC')
        ->getQuery()
        ->getResult()
        ;
    }
    public function findBybrouillonisread($user)
    {
        return $this->createQueryBuilder('u')
            

        ->andWhere('u.brouillon = :brouillon')
        ->andWhere('u.sender = :user')
   
        ->andWhere('u.is_read = :number')

        ->setParameter('brouillon', 1)
        ->setParameter('user', $user)
        ->setParameter('number', 0)
        ->orderBy('u.id', 'DESC')
        ->getQuery()
        ->getResult()
        ;
    }

    public function findBysuppisread($user)
    {
        return $this->createQueryBuilder('u')
            

        ->andWhere('u.supprimer = :supprimer')
        ->andWhere('u.sender = :user')
   
        ->andWhere('u.is_read = :number')

        ->setParameter('supprimer', 1)
        ->setParameter('user', $user)
        ->setParameter('number', 0)
        ->orderBy('u.id', 'DESC')
        ->getQuery()
        ->getResult()
        ;
    }

    public function findByrecusisread($user)
    {
        return $this->createQueryBuilder('u')
            

        ->andWhere('u.supprimer = :supprimer')
        ->andWhere('u.brouillon = :supprimer')
        ->andWhere('u.sender = :user')
   
        ->andWhere('u.is_read = :number')

        ->setParameter('supprimer', 0)
        ->setParameter('user', $user)
        ->setParameter('number', 0)
        ->orderBy('u.id', 'DESC')
        ->getQuery()
        ->getResult()
        ;
    }

    
    public function findByuser($user)
    {
        return $this->createQueryBuilder('u')
            
        ->innerJoin('u.users', 'a')
        ->andWhere('a.id = :user')
   
        ->setParameter('user', $user)
        ->orderBy('u.id', 'DESC')
        ->getQuery()
        ->getResult()
        ;
    }
    public function findBysender($user)
    {
        return $this->createQueryBuilder('u')
            
  
        ->andWhere('u.sender = :user')
   
        ->setParameter('user', $user)
        ->orderBy('u.id', 'DESC')
        ->getQuery()
        ->getResult()
        ;
    }
    public function findBysenderisread($user)
    {
        return $this->createQueryBuilder('u')
            
  
        ->andWhere('u.sender = :user')
        ->andWhere('u.is_read = :number')
        ->setParameter('user', $user)
        ->setParameter('number', 0)
        ->orderBy('u.id', 'DESC')
        ->getQuery()
        ->getResult()
        ;
    }
    
    public function findBysupp($user)
    {
        return $this->createQueryBuilder('u')
            
  
        ->andWhere('u.supprimer = :supprimer')
        ->andWhere('u.sender = :user')
   


        ->setParameter('supprimer', 1)
        ->setParameter('user', $user)
        ->orderBy('u.id', 'DESC')
        ->getQuery()
        ->getResult()
        ;
    }
}
