<?php

namespace App\Controller;

use App\Entity\Indispensables;
use App\Form\IndispensablesType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\IndispensablesRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class IndispensablesController extends AbstractController
{
    
/**
* @Route("/indispensables", name="app_indispensables_index", methods={"GET"})
 */
    public function index(IndispensablesRepository $IndispensablesRepository): Response
    {
      
        return $this->render('indispensables/index.html.twig', [
            'documents' => $IndispensablesRepository->findAll(),
        ]);
    }


/**
     * @Route("/indispensables/new", name="app_indispensables_new", methods={"GET", "POST"})
     */
    public function new(Request $request, IndispensablesRepository $IndispensablesRepository,EntityManagerInterface $entityManager): Response
    {
        
        $document = new Indispensables();
        $form = $this->createForm(IndispensablesType::class, $document);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $date = new \DateTimeImmutable('now');
         
            $document->setCreatedBy($this->getUser()->getEmail());

            
            $document->setCreatedAt($date);
            
            $document ->setNom( $form->get('nom')->getData());
            $document ->setType( $form->get('type')->getData());
            $file = $form->get('files')->getData();

                // On génère un nouveau nom de fichier
                $fichier = md5(uniqid()).'.'.$file->guessExtension();
                                
                // On copie le fichier dans le dossier uploads
                $file->move(
                    $this->getParameter('videos_directory'),
                    $fichier
                );

                $document ->setDocument($fichier);


                $entityManager->persist($document);
                $entityManager->flush();
            return $this->redirectToRoute('app_indispensables_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('indispensables/new.html.twig', [
            'document' => $document,
            'form' => $form,
          
        ]);
    }

    /**
     * @Route("/indispensables/{id}", name="app_indispensables_show", methods={"GET"})
     */
    public function show(Indispensables $document): Response
    {
        return $this->render('indispensables/show.html.twig', [
            'document' => $document,
           
        ]);
    }

    /**
     * @Route("/indispensables/{id}/edit", name="app_indispensables_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Indispensables $document, IndispensablesRepository $IndispensablesRepository): Response
    {
        $form = $this->createForm(IndispensableType::class, $document);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $IndispensablesRepository->add($document);
            return $this->redirectToRoute('app_indispensables_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('indispensabless/edit.html.twig', [
            'document' => $document,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/indispensables/{id}", name="app_indispensables_delete", methods={"POST"})
     */
    public function delete(Request $request, Indispensables $document, IndispensablesRepository $IndispensablesRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$document->getId(), $request->request->get('_token'))) {
            $IndispensablesRepository->remove($document);
        }

        return $this->redirectToRoute('app_indispensables_index', [], Response::HTTP_SEE_OTHER);
    }


}
