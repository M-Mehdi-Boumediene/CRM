<?php

namespace App\Controller;

use App\Entity\Administratifs;
use App\Form\AdministratifsType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\AdministratifsRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdministratifsController extends AbstractController
{
   /**
     * @Route("/administratifs", name="app_administratifs_index", methods={"GET"})
     */
    public function index(AdministratifsRepository $AdministratifsRepository): Response
    {
      
        return $this->render('administratifs/index.html.twig', [
            'documents' => $AdministratifsRepository->findAll(),
        ]);
    }


 /**
     * @Route("/administratifs/new", name="app_administratifs_new", methods={"GET", "POST"})
     */
    public function new(Request $request, AdministratifsRepository $AdministratifsRepository,EntityManagerInterface $entityManager): Response
    {
        $document= new Administratifs();
   
        $form = $this->createForm(AdministratifsType::class, $document);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $date = new \DateTimeImmutable('now');
         
            $document->setCreatedBy($this->getUser()->getEmail());

            
            $document->setCreatedAt($date);
        
        
            // $document ->setDocument($form->get('type')->getData());
           
            $file = $form->get('files')->getData();

                // On génère un nouveau nom de fichier
                $fichier = md5(uniqid()).'.'.$file->guessExtension();
                                
                // On copie le fichier dans le dossier uploads
                $file->move(
                    $this->getParameter('videos_directory'),
                    $fichier
                );
                $document ->setNom( $form->get('nom')->getData());
                $document ->setType( $form->get('type')->getData());
                $document ->setDocument($fichier);


                $entityManager->persist($document);
                $entityManager->flush();
            return $this->redirectToRoute('app_administratifs_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('administratifs/new.html.twig', [
            'document' => $document,
            'form' => $form,
          
        ]);
    }

    /**
     * @Route("/administratifs/{id}", name="app_administratifs_show", methods={"GET"})
     */
    public function show(Administratifs $document): Response
    {
        return $this->render('administratifs/show.html.twig', [
            'document' => $document,
           
        
    
        ]);
    }

    /**
     * @Route("/administratifs/{id}/edit", name="app_administratifs_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Administratifs $document, AdministratifsRepository $AdministratifsRepository): Response
    {
        $form = $this->createForm(AdministratifsType::class, $document);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $AdministratifsRepository->add($document);
            return $this->redirectToRoute('app_administratifs_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('administratifs/edit.html.twig', [
            'document' => $document,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/administratifs/{id}", name="app_administratifs_delete", methods={"POST"})
     */
    public function delete(Request $request, Administratifs $document, AdministratifsRepository $AdministratifsRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$document->getId(), $request->request->get('_token'))) {
            $AdministratifsRepository->remove($document);
        }

        return $this->redirectToRoute('app_administratifs_index', [], Response::HTTP_SEE_OTHER);
    }
}
