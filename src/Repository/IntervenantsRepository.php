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
        ->innerJoin('e.classes', 'a')
        ->where('a.id = :val')
        ->setParameter('val', $classe)
   
  
        ->getQuery()
        ->getResult()
        ;
    }


    public function searchMot($value,$module,$classe)
    {


        if(!empty($value)){

            return $this->createQueryBuilder('u')
        
            ->orWhere('u.nom LIKE :value')
            ->orWhere('u.prenom LIKE :value')
            ->orWhere('u.email LIKE :value')
            ->orWhere('u.telephone LIKE :value')
            ->setParameter('value', '%'.$value.'%')
  

            ->orderBy('u.id', 'ASC')
            ->getQuery()
            ->getResult()
            ;
        }


        if(!empty($module)){

            return $this->createQueryBuilder('u')

          

            ->andWhere('u.cat = :module')

            ->setParameter('module', $module)

            ->orderBy('u.id', 'ASC')
            ->getQuery()
            ->getResult()
            ;
        }


        if(!empty($classe)){

            return $this->createQueryBuilder('u')
    
            ->andWhere('u.classes = :classe')

            ->setParameter('classe', $classe)

            ->orderBy('u.id', 'ASC')
            ->getQuery()
            ->getResult()
            ;
        }


       
    }

}
