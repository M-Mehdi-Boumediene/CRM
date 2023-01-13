<?php

namespace App\Repository;

use App\Entity\TableauNotes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TableauNotes>
 *
 * @method TableauNotes|null find($id, $lockMode = null, $lockVersion = null)
 * @method TableauNotes|null findOneBy(array $criteria, array $orderBy = null)
 * @method TableauNotes[]    findAll()
 * @method TableauNotes[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TableauNotesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TableauNotes::class);
    }

    public function add(TableauNotes $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(TableauNotes $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }


    public function searchMot($value,$classe,$module,$apprenant)
    {

        if(!empty($value)){

            return $this->createQueryBuilder('u')
        
            ->andWhere('u.nom LIKE :value')
            
            ->setParameter('value', '%'.$value.'%')
      
            ->orderBy('u.id', 'ASC')
            ->getQuery()
            ->getResult()
            
        ;
        }

        if(!empty($classe)){
            return $this->createQueryBuilder('u')
            ->innerJoin('u.notes', 'n')
            ->andWhere('n.classes = :classe')
            ->setParameter('classe', $classe)

            ->getQuery()
            ->getResult()
        ;
        }

        if(!empty($module)){
            return $this->createQueryBuilder('u')



            ->innerJoin('u.notes', 'n')
            ->andWhere('n.module = :module')
    
 
            ->setParameter('module', $module)
        
            ->getQuery()
            ->getResult()
        ;
        }
 
       if(!empty($apprenant)){
            return $this->createQueryBuilder('u')
            ->innerJoin('u.notes', 'n')
            ->andWhere('n.etudiantid = :etudiant')
            ->setParameter('etudiant', $apprenant)

            ->getQuery()
            ->getResult()
        ;
        }
        
    }
    public function paretudiant1($etudiant)
    {
        return $this->createQueryBuilder('u')

            ->innerJoin('u.etudiant', 'c')
            ->innerJoin('u.notes', 'n')
            ->andWhere('c.id = :etudiant')
      
    
            ->setParameter('etudiant', $etudiant)
            
      


            ->orderBy('u.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }
    public function paretudiant1exam($etudiant)
    {
        return $this->createQueryBuilder('u')

            ->innerJoin('u.etudiant', 'c')
            ->innerJoin('u.notes', 'n')
            ->andWhere('c.id = :etudiant')
            ->andWhere('n.semestre = :semestre')
            ->andWhere('n.type = :type')
            ->setParameter('etudiant', $etudiant)
            ->setParameter('semestre', 1)
            ->setParameter('type', 'EXAMEN')


            ->orderBy('u.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }
    public function paretudiant2($etudiant)
    {
        return $this->createQueryBuilder('u')

            ->innerJoin('u.etudiant', 'c')
            ->innerJoin('u.notes', 'n')
            ->andWhere('c.id = :etudiant')
            ->andWhere('n.semestre = :semestre')
            ->andWhere('n.type = :type')
            ->setParameter('etudiant', $etudiant)
            ->setParameter('semestre', 2)
            ->setParameter('type', 'DEVOIR')

            ->orderBy('u.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    public function paretudiant2exam($etudiant)
    {
        return $this->createQueryBuilder('u')

            ->innerJoin('u.etudiant', 'c')
            ->innerJoin('u.notes', 'n')
            ->andWhere('c.id = :etudiant')
            ->andWhere('n.semestre = :semestre')
            ->andWhere('n.type = :type')
            ->setParameter('etudiant', $etudiant)
            ->setParameter('semestre', 2)
            ->setParameter('type', 'EXAMEN')

            ->orderBy('u.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }
    public function paretudiant3($etudiant)
    {
        return $this->createQueryBuilder('u')

            ->innerJoin('u.etudiant', 'c')
            ->innerJoin('u.notes', 'n')
            ->andWhere('c.id = :etudiant')
            ->andWhere('n.semestre = :semestre')
            ->andWhere('n.type = :type')
            ->setParameter('etudiant', $etudiant)
            ->setParameter('semestre', 3)
            ->setParameter('type', 'DEVOIR')

            ->orderBy('u.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    public function paretudiant3exam($etudiant)
    {
        return $this->createQueryBuilder('u')

            ->innerJoin('u.etudiant', 'c')
            ->innerJoin('u.notes', 'n')
            ->andWhere('c.id = :etudiant')
            ->andWhere('n.semestre = :semestre')
            ->andWhere('n.type = :type')
            ->setParameter('etudiant', $etudiant)
            ->setParameter('semestre', 3)
            ->setParameter('type', 'EXAMEN')

            ->orderBy('u.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }


    public function paretudiant4($etudiant)
    {
        return $this->createQueryBuilder('u')

            ->innerJoin('u.etudiant', 'c')
            ->innerJoin('u.notes', 'n')
            ->andWhere('c.id = :etudiant')
            ->andWhere('n.semestre = :semestre')
            ->andWhere('n.type = :type')
            ->setParameter('etudiant', $etudiant)
            ->setParameter('semestre', 4)
            ->setParameter('type', 'DEVOIR')

            ->orderBy('u.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }
    public function paretudiant4exam($etudiant)
    {
        return $this->createQueryBuilder('u')

            ->innerJoin('u.etudiant', 'c')
            ->innerJoin('u.notes', 'n')
            ->andWhere('c.id = :etudiant')
            ->andWhere('n.semestre = :semestre')
            ->andWhere('n.type = :type')
            ->setParameter('etudiant', $etudiant)
            ->setParameter('semestre', 4)
            ->setParameter('type', 'EXAMEN')

            ->orderBy('u.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }
    public function findByetudiant($ee,$lemodule,$lesemestre,$letype)
    {
        return $this->createQueryBuilder('t')
        ->innerJoin('t.etudiant', 'c')
        ->innerJoin('t.notes', 'n')
           ->andWhere('c.id = :etudiant')
           ->andWhere('n.module = :module')
           ->andWhere('n.semestre = :semestre')
           ->andWhere('n.type = :type')
            ->setParameter('etudiant', $ee)
        
            ->setParameter('module', $lemodule)
            ->setParameter('semestre', $lesemestre)
            ->setParameter('type', $letype)
            ->getQuery()
            ->getOneOrNullResult()
      ;
    }

//    /**
//     * @return TableauNotes[] Returns an array of TableauNotes objects
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

//    public function findOneBySomeField($value): ?TableauNotes
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
