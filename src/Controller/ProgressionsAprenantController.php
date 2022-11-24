<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\EtudiantsRepository;
use App\Repository\TableauNotesRepository;
use App\Repository\ClassesRepository;
use App\Entity\Etudiants;
use App\Repository\AbsencesRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProgressionsAprenantController extends AbstractController
{
    /**
     * @Route("/progressions/aprenant", name="app_progressions_aprenant")
     */
    public function index( EtudiantsRepository $etudiantsRepository,  AbsencesRepository $absencesRepository, TableauNotesRepository $TableauNotesRepository, ClassesRepository $classesRepository): Response
    {

        $delay = new \Datetime('last month');
        $day = new \Datetime('last day');

        $user = $this->getUser();
        $classe = $user->getClasse();
        $etudiant = $etudiantsRepository->findByUser(user);

        $tableaunotes = $TableauNotesRepository->paretudiant1($etudiant);

        $tableaunotes2 = $TableauNotesRepository->paretudiant2($etudiant);
        
        $tableaunotes3 = $TableauNotesRepository->paretudiant3($etudiant);
        $tableaunotes4 = $TableauNotesRepository->paretudiant4($etudiant);

        $tableaunotesexam = $TableauNotesRepository->paretudiant1exam($etudiant);
        $tableaunotes2exam = $TableauNotesRepository->paretudiant2exam($etudiant);
        $tableaunotes3exam = $TableauNotesRepository->paretudiant3exam($etudiant);
        $tableaunotes4exam = $TableauNotesRepository->paretudiant4exam($etudiant);

        return $this->render('progressions_aprenant/index.html.twig', [
            'controller_name' => 'ProgressionsAprenantController',
            'tableaunotes' => $tableaunotes,
            'tableaunotesexam' => $tableaunotesexam,
            'tableaunotes2' => $tableaunotes2,
            'tableaunotes2exam' => $tableaunotes2exam,
            'tableaunotes3' => $tableaunotes3,
            'tableaunotes3exam' => $tableaunotes3exam,
            'tableaunotes4' => $tableaunotes4,
            'tableaunotes4exam' => $tableaunotes4exam,
            'etudiant' => $etudiant,
         
        ]);
    }
}