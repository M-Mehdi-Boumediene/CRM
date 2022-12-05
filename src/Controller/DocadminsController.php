<?php

namespace App\Controller;

use App\Entity\Docadmins;
use App\Form\DocadminsType;
use App\Entity\Files;
use App\Repository\DocadminsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/docadmins')]
class DocadminsController extends AbstractController
{
    #[Route('/', name: 'app_docadmins_index', methods: ['GET'])]
    public function index(DocadminsRepository $docadminsRepository): Response
    {
        return $this->render('docadmins/index.html.twig', [
            'docadmins' => $docadminsRepository->findBy(array('user'=>$this->getUser())),
        ]);
    }

    #[Route('/new', name: 'app_docadmins_new', methods: ['GET', 'POST'])]
    public function new(Request $request, DocadminsRepository $docadminsRepository): Response
    {
        $docadmin = new Docadmins();
        $form = $this->createForm(DocadminsType::class, $docadmin);
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
                $docadmin->setNom($fichier);
                $docadmin->setCreatedAt($date);
              
                $file->setNom($form->get('nom')->getData());
                $docadmin->addFile($file);

            }

          
            $docadminsRepository->add($docadmin, true);

            return $this->redirectToRoute('app_docadmins_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('docadmins/new.html.twig', [
            'docadmin' => $docadmin,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_docadmins_show', methods: ['GET'])]
    public function show(Docadmins $docadmin): Response
    {
        return $this->render('docadmins/show.html.twig', [
            'docadmin' => $docadmin,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_docadmins_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Docadmins $docadmin, DocadminsRepository $docadminsRepository): Response
    {
        $form = $this->createForm(DocadminsType::class, $docadmin);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $docadminsRepository->add($docadmin, true);

            return $this->redirectToRoute('app_docadmins_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('docadmins/edit.html.twig', [
            'docadmin' => $docadmin,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_docadmins_delete', methods: ['POST'])]
    public function delete(Request $request, Docadmins $docadmin, DocadminsRepository $docadminsRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$docadmin->getId(), $request->request->get('_token'))) {
            $docadminsRepository->remove($docadmin, true);
        }

        return $this->redirectToRoute('app_docadmins_index', [], Response::HTTP_SEE_OTHER);
    }
}
