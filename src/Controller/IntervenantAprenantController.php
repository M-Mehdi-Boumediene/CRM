<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ClassesRepository;
use App\Repository\IntervenantsRepository;
class IntervenantAprenantController extends AbstractController
{
    /**
     * @Route("/intervenant/aprenant", name="app_intervenant_aprenant")
     */
    public function index(ClassesRepository $classesRepository,IntervenantsRepository $intervenantsRepository): Response
    {
        $user = $this->getUser();
        
       
        
        $intervenant = $intervenantsRepository->findOneBy(array('email'=>$user->getEmail()));
        $id = $intervenant->getClasses();



        return $this->renderForm('intervenant_aprenant/index.html.twig', [
      
            'classes' => $classesRepository->findByIntervenantEtudiant($id),
        ]);
    
    }
}
