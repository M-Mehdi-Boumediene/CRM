<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Classes;
use App\Entity\Blocs;


class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $classe = new Classes();
        $classe2 = new Classes();
        $classe3 = new Classes();
        $classe4 = new Classes();
        $classe5 = new Classes();

        $classe->setNom('Master informatique');
       $classe->setAnneescolaire('2022-2023');
       $classe->setNbsemestre(4);
       $classe->setCursus('Initial');

       $classe2->setNom('Master management');
       $classe2->setAnneescolaire('2022-2023');
       $classe2->setNbsemestre(4);
       $classe2->setCursus('Initial');

       $classe3->setNom('Master biologie');
       $classe3->setAnneescolaire('2022-2023');
       $classe3->setNbsemestre(4);
       $classe3->setCursus('Alternance');

       $classe4->setNom('Master finance');
       $classe4->setAnneescolaire('2022-2023');
       $classe4->setNbsemestre(4);
       $classe4->setCursus('Initial');

       $classe5->setNom('Design');
       $classe5->setAnneescolaire('2022-2023');
       $classe5->setNbsemestre(4);
       $classe5->setCursus('Alternance');

         $manager->persist($classe,$classe2,$classe3,$classe4,$classe5);




         $bloc = new Blocs();
         $bloc2 = new Blocs();
         $bloc3 = new Blocs();
         $bloc4 = new Blocs();
         $bloc5 = new Blocs();
 
        $bloc->setNom('Bloc 1');
        $bloc->setClasse($classe);
        $bloc->setCoefficient(2);
      

        $bloc2->setNom('Bloc 2');
        $bloc2->setClasse($classe2);
        $bloc2->setCoefficient(2);

        $bloc3->setNom('Bloc 3');
        $bloc3->setClasse($classe3);
        $bloc3->setCoefficient(2);

        $bloc4->setNom('Bloc 4');
        $bloc4->setClasse($classe4);
        $bloc4->setCoefficient(2);

        $bloc5->setNom('Bloc 5');
        $bloc5->setClasse($classe5);
        $bloc5->setCoefficient(2);
 
          $manager->persist($bloc,$bloc2,$bloc3,$bloc4,$bloc5);

        $manager->flush();
    }
}
