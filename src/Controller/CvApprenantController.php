<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\IntervenantsRepository;
use App\Entity\Cv;
use App\Form\CvType;
use App\Repository\CvRepository;

class CvApprenantController extends AbstractController
{
    #[Route('/cv/apprenant', name: 'app_cv_apprenant', methods: ['GET', 'POST'])]
    public function index(Request $request, IntervenantsRepository $intervenantsRepository, CvRepository $cvRepository): Response
    {

        $user = $this->getUser();
        $role = $this->getUser()->getRoles();

        $intervenant = $intervenantsRepository->findOneBy(array('user'=>$user));
        $experience = $cvRepository->findBy(array('intervenant'=>$intervenant,'type'=>'Expérience'));
        $formation = $cvRepository->findBy(array('intervenant'=>$intervenant,'type'=>'Formation'));

        $cv = new Cv();
        $form = $this->createForm(CvType::class, $cv);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $cv->setIntervenant($intervenant);
            $cvRepository->add($cv, true);

            return $this->redirectToRoute('app_cv_apprenant', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('cv_apprenant/index.html.twig', [
            'controller_name' => 'CvApprenantController',
            'intervenant'=>$intervenant,
            'form' => $form->createView(),
            'experiences'=>$experience,
            'formations'=>$formation,
        ]);
    }
}