<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OffresController extends AbstractController
{
    
    #[Route('/offres_emploi', name: 'app_offres_emlpoi')]
    public function index(): Response
    {
        return $this->render('offres/index.html.twig', [
            'controller_name' => 'OffresController',
        ]);
    }

}
