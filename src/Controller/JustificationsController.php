<?php

namespace App\Controller;

use App\Entity\Justifications;
use App\Form\JustificationsType;
use App\Repository\JustificationsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/justifications')]
class JustificationsController extends AbstractController
{
    #[Route('/', name: 'app_justifications_index', methods: ['GET'])]
    public function index(JustificationsRepository $justificationsRepository): Response
    {
        return $this->render('justifications/index.html.twig', [
            'justifications' => $justificationsRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_justifications_new', methods: ['GET', 'POST'])]
    public function new(Request $request, JustificationsRepository $justificationsRepository): Response
    {
        $justification = new Justifications();
        $form = $this->createForm(JustificationsType::class, $justification);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $justificationsRepository->add($justification, true);

            return $this->redirectToRoute('app_justifications_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('justifications/new.html.twig', [
            'justification' => $justification,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_justifications_show', methods: ['GET'])]
    public function show(Justifications $justification): Response
    {
        return $this->render('justifications/show.html.twig', [
            'justification' => $justification,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_justifications_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Justifications $justification, JustificationsRepository $justificationsRepository): Response
    {
        $form = $this->createForm(JustificationsType::class, $justification);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $justificationsRepository->add($justification, true);

            return $this->redirectToRoute('app_justifications_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('justifications/edit.html.twig', [
            'justification' => $justification,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_justifications_delete', methods: ['POST'])]
    public function delete(Request $request, Justifications $justification, JustificationsRepository $justificationsRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$justification->getId(), $request->request->get('_token'))) {
            $justificationsRepository->remove($justification, true);
        }

        return $this->redirectToRoute('app_justifications_index', [], Response::HTTP_SEE_OTHER);
    }
}
