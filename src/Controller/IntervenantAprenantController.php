<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ClassesRepository;

class IntervenantAprenantController extends AbstractController
{
    /**
     * @Route("/intervenant/aprenant", name="app_intervenant_aprenant")
     */
    public function index(ClassesRepository $classesRepository): Response
    {
        $user = $this->getUser();
        $intervenant = $user->getIntervenants();

        foreach ($intervenant as $inter){
          $classe =  $inter->getClasses();
        }

       

        return $this->render('intervenant_aprenant/index.html.twig', [
            'controller_name' => 'IntervenantAprenantController',
            'classes' => $classesRepository->findByIntervenantEtudiant($classe),
        ]);
    }
}
