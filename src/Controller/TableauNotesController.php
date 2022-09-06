<?php

namespace App\Controller;

use App\Entity\TableauNotes;
use App\Form\TableauNotesType;
use App\Repository\TableauNotesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/tableau/notes')]
class TableauNotesController extends AbstractController
{
    #[Route('/', name: 'app_tableau_notes_index', methods: ['GET'])]
    public function index(TableauNotesRepository $tableauNotesRepository): Response
    {
        return $this->render('tableau_notes/index.html.twig', [
            'tableau_notes' => $tableauNotesRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_tableau_notes_new', methods: ['GET', 'POST'])]
    public function new(Request $request, TableauNotesRepository $tableauNotesRepository): Response
    {
        $tableauNote = new TableauNotes();
        $form = $this->createForm(TableauNotesType::class, $tableauNote);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $tableauNotesRepository->add($tableauNote, true);

            return $this->redirectToRoute('app_tableau_notes_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('tableau_notes/new.html.twig', [
            'tableau_note' => $tableauNote,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_tableau_notes_show', methods: ['GET'])]
    public function show(TableauNotes $tableauNote): Response
    {
        return $this->render('tableau_notes/show.html.twig', [
            'tableau_note' => $tableauNote,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_tableau_notes_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, TableauNotes $tableauNote, TableauNotesRepository $tableauNotesRepository): Response
    {
        $form = $this->createForm(TableauNotesType::class, $tableauNote);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $tableauNotesRepository->add($tableauNote, true);

            return $this->redirectToRoute('app_notes_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('tableau_notes/edit.html.twig', [
            'tableau_note' => $tableauNote,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_tableau_notes_delete', methods: ['POST'])]
    public function delete(Request $request, TableauNotes $tableauNote, TableauNotesRepository $tableauNotesRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$tableauNote->getId(), $request->request->get('_token'))) {
            $tableauNotesRepository->remove($tableauNote, true);
        }

        return $this->redirectToRoute('app_tableau_notes_index', [], Response::HTTP_SEE_OTHER);
    }
}
