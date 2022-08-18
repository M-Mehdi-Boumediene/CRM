<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProgressionsAprenantController extends AbstractController
{
    /**
     * @Route("/progressions/aprenant", name="app_progressions_aprenant")
     */
    public function index(): Response
    {
        return $this->render('progressions_aprenant/index.html.twig', [
            'controller_name' => 'ProgressionsAprenantController',
        ]);
    }
}
