<?php

namespace App\Controller;

use App\Entity\Absintervenants;
use App\Form\AbsintervenantsType;
use App\Repository\AbsintervenantsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/absintervenants')]
class AbsintervenantsController extends AbstractController
{
    #[Route('/', name: 'app_absintervenants_index', methods: ['GET'])]
    public function index(AbsintervenantsRepository $absintervenantsRepository): Response
    {
        return $this->render('absintervenants/index.html.twig', [
            'absintervenants' => $absintervenantsRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_absintervenants_new', methods: ['GET', 'POST'])]
    public function new(Request $request, AbsintervenantsRepository $absintervenantsRepository): Response
    {
        $absintervenant = new Absintervenants();
        $form = $this->createForm(AbsintervenantsType::class, $absintervenant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $absintervenantsRepository->add($absintervenant, true);

            return $this->redirectToRoute('app_absintervenants_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('absintervenants/new.html.twig', [
            'absintervenant' => $absintervenant,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_absintervenants_show', methods: ['GET'])]
    public function show(Absintervenants $absintervenant): Response
    {
        return $this->render('absintervenants/show.html.twig', [
            'absintervenant' => $absintervenant,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_absintervenants_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Absintervenants $absintervenant, AbsintervenantsRepository $absintervenantsRepository): Response
    {
        $form = $this->createForm(AbsintervenantsType::class, $absintervenant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $absintervenantsRepository->add($absintervenant, true);

            return $this->redirectToRoute('app_absintervenants_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('absintervenants/edit.html.twig', [
            'absintervenant' => $absintervenant,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_absintervenants_delete', methods: ['POST'])]
    public function delete(Request $request, Absintervenants $absintervenant, AbsintervenantsRepository $absintervenantsRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$absintervenant->getId(), $request->request->get('_token'))) {
            $absintervenantsRepository->remove($absintervenant, true);
        }

        return $this->redirectToRoute('app_absintervenants_index', [], Response::HTTP_SEE_OTHER);
    }
}
