<?php

namespace App\Controller;

use App\Entity\Telechargements;
use App\Entity\Files;
use App\Form\TelechargementsType;
use App\Repository\TelechargementsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/telechargements")
 */
class TelechargementsController extends AbstractController
{
    /**
     * @Route("/", name="app_telechargements_index", methods={"GET"})
     */
    public function index(TelechargementsRepository $telechargementsRepository): Response
    {
        return $this->render('telechargements/index.html.twig', [
            'telechargements' => $telechargementsRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_telechargements_new", methods={"GET", "POST"})
     */
    public function new(Request $request, TelechargementsRepository $telechargementsRepository): Response
    {
        $telechargement = new Telechargements();
        $form = $this->createForm(TelechargementsType::class, $telechargement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $files = $form->get('files')->getData();

            // Je boucle sur les documents
            foreach($files as $file){
                // Je génère un nouveau nom de fichier
                $fichier = md5(uniqid()) . '.' . $file->guessExtension();

                // Je copie le fichier dans le dossier uploads
                $file->move(
                    $this->getParameter('videos_directory'),
                    $fichier
                );

                // Je stocke le document dans la BDD (nom du fichier)
                $file= new Files();
                $date = new \DateTimeImmutable('now');
                $file->setName($fichier);
                $telechargement->setName($fichier);
                $telechargement->setCreatedAt($date);
                $telechargement->setUser($this->getUser());
                $file->setNom($form->get('nom')->getData());
                $telechargement->addFile($file);

            }

            $telechargementsRepository->add($telechargement);
            return $this->redirectToRoute('app_telechargements_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('telechargements/new.html.twig', [
            'telechargement' => $telechargement,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_telechargements_show", methods={"GET"})
     */
    public function show(Telechargements $telechargement): Response
    {
        return $this->render('telechargements/show.html.twig', [
            'telechargement' => $telechargement,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_telechargements_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Telechargements $telechargement, TelechargementsRepository $telechargementsRepository): Response
    {
        $form = $this->createForm(TelechargementsType::class, $telechargement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $telechargementsRepository->add($telechargement);
            return $this->redirectToRoute('app_telechargements_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('telechargements/edit.html.twig', [
            'telechargement' => $telechargement,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_telechargements_delete", methods={"POST"})
     */
    public function delete(Request $request, Telechargements $telechargement, TelechargementsRepository $telechargementsRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$telechargement->getId(), $request->request->get('_token'))) {
            $telechargementsRepository->remove($telechargement);
        }

        return $this->redirectToRoute('app_telechargements_index', [], Response::HTTP_SEE_OTHER);
    }
}
