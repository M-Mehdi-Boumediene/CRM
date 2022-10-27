<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OffresController extends AbstractController
{
    #[Route('/offres-emploi', name: 'app_offres-emlpoi')]
    public function index(): Response
    {
        return $this->render('offres/index.html.twig', [
            'controller_name' => 'OffresController',
        ]);
    }

    #[Route('/offres-stage', name: 'app_offres-stage')]
    public function indexstage(): Response
    {
        return $this->render('offres/indexstage.html.twig', [
            'controller_name' => 'OffresController',
        ]);
    }
}
