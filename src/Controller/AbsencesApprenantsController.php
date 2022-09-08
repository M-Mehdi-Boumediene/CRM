<?php

namespace App\Controller;
use App\Entity\Absences;
use App\Form\AbsencesType;
use App\Repository\AbsencesRepository;
use App\Repository\EtudiantsRepository;
use App\Repository\TableauAbsencesRepository;
use App\Repository\TableauNotesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AbsencesApprenantsController extends AbstractController
{
    /**
     * @Route("/absences/apprenants", name="app_absences_apprenants")
     */
    public function index(AbsencesRepository $absencesRepository,TableauAbsencesRepository $TableauAbsencesRepository,TableauNotesRepository $TableauNotesRepository, EtudiantsRepository $apprenants ): Response
    {
        $user = $this->getUser();
        $etudiants = $apprenants->findBy(array('user'=>$user));

        return $this->render('absences_apprenants/index.html.twig', [
            'controller_name' => 'AbsencesApprenantsController',

            'absences' => $TableauAbsencesRepository->paretudiant($etudiants),
            'retard' => $TableauAbsencesRepository->paretudiant($etudiants),

        ]);
    }
}
