<?php

namespace App\Controller;

use App\Entity\Entreprises;
use App\Form\EntreprisesType;
use App\Form\FiltreType;
use App\Form\FiltreEntrepriseType;
use App\Repository\EntreprisesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/entreprises")
 */
class EntreprisesController extends AbstractController
{
    /**
     * @Route("/", name="app_entreprises_index", methods={"GET", "POST"})
     */
    public function index(Request $request, EntreprisesRepository $entreprisesRepository): Response
    {
        $form = $this->createForm(FiltreType::class);
        $form->handleRequest($request);

        $form2 = $this->createForm(FiltreEntrepriseType::class);
        $form2->handleRequest($request);
        
        $value = $form2->get('search')->getData();
        $entreprises =  $entreprisesRepository->searchMot($value);
        if ($form2->isSubmitted() && $form2->isValid()) {

      
            return $this->renderForm('entreprises/index.html.twig', [
                'entreprises' => $entreprises,
                'form' => $form,
                'form2' => $form2,
            ]);

        }

        return $this->renderForm('entreprises/index.html.twig', [
            'entreprises' => $entreprisesRepository->findAll(),
            'form2' => $form2,
        ]);
    }

    /**
     * @Route("/new", name="app_entreprises_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntreprisesRepository $entreprisesRepository): Response
    {
        $entreprise = new Entreprises();
        $form = $this->createForm(EntreprisesType::class, $entreprise);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $date = new \DateTimeImmutable('now');
         
            $entreprise->setCreatedBy($this->getUser()->getEmail());
         
            $entreprise->setCreatedAt($date);
            $entreprisesRepository->add($entreprise);
            return $this->redirectToRoute('app_entreprises_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('entreprises/new.html.twig', [
            'entreprise' => $entreprise,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_entreprises_show", methods={"GET"})
     */
    public function show(Entreprises $entreprise): Response
    {
        return $this->render('entreprises/show.html.twig', [
            'entreprise' => $entreprise,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_entreprises_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Entreprises $entreprise, EntreprisesRepository $entreprisesRepository): Response
    {
        $form = $this->createForm(EntreprisesType::class, $entreprise);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entreprisesRepository->add($entreprise);
            return $this->redirectToRoute('app_entreprises_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('entreprises/edit.html.twig', [
            'entreprise' => $entreprise,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_entreprises_delete", methods={"POST"})
     */
    public function delete(Request $request, Entreprises $entreprise, EntreprisesRepository $entreprisesRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$entreprise->getId(), $request->request->get('_token'))) {
            $entreprisesRepository->remove($entreprise);
        }

        return $this->redirectToRoute('app_entreprises_index', [], Response::HTTP_SEE_OTHER);
    }
}
