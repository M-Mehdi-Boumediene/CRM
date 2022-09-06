<?php

namespace App\Controller;

use App\Entity\TableauAbsences;
use App\Form\TableauAbsencesType;
use App\Repository\TableauAbsencesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/tableau/absences')]
class TableauAbsencesController extends AbstractController
{
    #[Route('/', name: 'app_tableau_absences_index', methods: ['GET'])]
    public function index(TableauAbsencesRepository $tableauAbsencesRepository): Response
    {
        return $this->render('tableau_absences/index.html.twig', [
            'tableau_absences' => $tableauAbsencesRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_tableau_absences_new', methods: ['GET', 'POST'])]
    public function new(Request $request, TableauAbsencesRepository $tableauAbsencesRepository): Response
    {
        $tableauAbsence = new TableauAbsences();
        $form = $this->createForm(TableauAbsencesType::class, $tableauAbsence);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $tableauAbsencesRepository->add($tableauAbsence, true);

            return $this->redirectToRoute('app_tableau_absences_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('tableau_absences/new.html.twig', [
            'tableau_absence' => $tableauAbsence,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_tableau_absences_show', methods: ['GET'])]
    public function show(TableauAbsences $tableauAbsence): Response
    {
        return $this->render('tableau_absences/show.html.twig', [
            'tableau_absence' => $tableauAbsence,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_tableau_absences_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, TableauAbsences $tableauAbsence, TableauAbsencesRepository $tableauAbsencesRepository): Response
    {
        $form = $this->createForm(TableauAbsencesType::class, $tableauAbsence);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $tableauAbsencesRepository->add($tableauAbsence, true);

            return $this->redirectToRoute('app_tableau_absences_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('tableau_absences/edit.html.twig', [
            'tableau_absence' => $tableauAbsence,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_tableau_absences_delete', methods: ['POST'])]
    public function delete(Request $request, TableauAbsences $tableauAbsence, TableauAbsencesRepository $tableauAbsencesRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$tableauAbsence->getId(), $request->request->get('_token'))) {
            $tableauAbsencesRepository->remove($tableauAbsence, true);
        }

        return $this->redirectToRoute('app_tableau_absences_index', [], Response::HTTP_SEE_OTHER);
    }
}
