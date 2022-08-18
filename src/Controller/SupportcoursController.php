<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Modules;
use App\Repository\UsersRepository;
use App\Repository\ModulesRepository;
use App\Repository\EtudiantsRepository;

class SupportcoursController extends AbstractController
{
    #[Route('/supportcours', name: 'app_supportcours')]
    public function index(ModulesRepository $modulesRepository,EtudiantsRepository $etudiantsRepository): Response
    {

        $user= $this->getUser();

        return $this->render('supportcours/index.html.twig', [
            'controller_name' => 'SupportcoursController',
        
            'etudiants' => $etudiantsRepository->findByUser($user),
        ]);
    }

     /**
     * @Route("/supportcours/{id}", name="app_supportcours_show", methods={"GET"})
     */
    public function show(Modules $module): Response
    {


        return $this->render('supportcours/show.html.twig', [
            'module' => $module,
          
        ]);
    }
}
