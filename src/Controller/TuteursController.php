<?php

namespace App\Controller;

use App\Entity\Tuteurs;
use App\Form\TuteursType;
use App\Repository\UsersRepository;
use App\Repository\TuteursRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/tuteurs")
 */
class TuteursController extends AbstractController
{
    /**
     * @Route("/", name="app_tuteurs_index", methods={"GET"})
     */
    public function index(TuteursRepository $tuteursRepository): Response
    {
        return $this->render('tuteurs/index.html.twig', [
            'tuteurs' => $tuteursRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_tuteurs_new", methods={"GET", "POST"})
     */
    public function new(Request $request, TuteursRepository $tuteursRepository): Response
    {
        $tuteur = new Tuteurs();
        $form = $this->createForm(TuteursType::class, $tuteur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $date = new \DateTimeImmutable('now');
         
            $tuteur->setCreatedBy($this->getUser()->getEmail());
            $tuteur->setUsers($this->getUser());
            $tuteur->setCreatedAt($date);
            $tuteursRepository->add($tuteur);
            return $this->redirectToRoute('app_tuteurs_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('tuteurs/new.html.twig', [
            'tuteur' => $tuteur,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_tuteurs_show", methods={"GET"})
     */
    public function show(Tuteurs $tuteur,UsersRepository $etudiantsRepository): Response
    {
        return $this->render('tuteurs/show.html.twig', [
            'tuteur' => $tuteur,
            'etudiants' => $etudiantsRepository->findByEtudiant(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_tuteurs_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Tuteurs $tuteur, TuteursRepository $tuteursRepository): Response
    {
        $form = $this->createForm(TuteursType::class, $tuteur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $tuteursRepository->add($tuteur);
            return $this->redirectToRoute('app_tuteurs_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('tuteurs/edit.html.twig', [
            'tuteur' => $tuteur,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_tuteurs_delete", methods={"POST"})
     */
    public function delete(Request $request, Tuteurs $tuteur, TuteursRepository $tuteursRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$tuteur->getId(), $request->request->get('_token'))) {
            $tuteursRepository->remove($tuteur);
        }

        return $this->redirectToRoute('app_tuteurs_index', [], Response::HTTP_SEE_OTHER);
    }
}
