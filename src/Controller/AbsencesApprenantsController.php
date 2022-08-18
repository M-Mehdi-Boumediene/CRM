<?php

namespace App\Controller;
use App\Entity\Absences;
use App\Form\AbsencesType;
use App\Repository\AbsencesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AbsencesApprenantsController extends AbstractController
{
    /**
     * @Route("/absences/apprenants", name="app_absences_apprenants")
     */
    public function index(AbsencesRepository $absencesRepository): Response
    {
        $user = $this->getUser();
        return $this->render('absences_apprenants/index.html.twig', [
            'controller_name' => 'AbsencesApprenantsController',
            'absences' => $absencesRepository->findByUser($user),

        ]);
    }
}
