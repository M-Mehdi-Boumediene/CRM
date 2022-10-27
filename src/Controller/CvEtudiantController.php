<?php

namespace App\Controller;

use App\Entity\Cv;
use App\Form\CvType;
use App\Entity\Etudiants;
use App\Repository\CvRepository;
use App\Repository\EtudiantsRepository;
use App\Repository\IntervenantsRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CvEtudiantController extends AbstractController
{
    #[Route('/cv/apprenant', name: 'app_cv_etudiant', methods: ['GET', 'POST'])]
    public function index(Request $request, EtudiantsRepository $etudiantsRepository, CvRepository $cvRepository): Response
    {
        
        $user = $this->getUser();
        $role = $this->getUser()->getRoles();

        $etudiant = $etudiantsRepository->findOneBy(array('user'=>$user));
        $experience = $cvRepository->findBy(array('etudiant'=>$etudiant,'type'=>'Expérience'));
        $formation = $cvRepository->findBy(array('etudiant'=>$etudiant,'type'=>'Formation'));

        $cv = new Cv();
        $form = $this->createForm(CvType::class, $cv);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $cv->setEtudiant($etudiant);
            $cvRepository->add($cv, true);

            return $this->redirectToRoute('app_cv_etudiant', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('cv_etudiant/index.html.twig', [
            'controller_name' => 'CvEtudiantController',
            'etudiant'=>$etudiant,
            'form' => $form->createView(),
            'experiences'=>$experience,
            'formations'=>$formation,
        ]);
    }
}