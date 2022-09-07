<?php

namespace App\Controller;

use App\Entity\Absences;
use App\Entity\TableauAbsences;
use App\Form\AbsencesType;
use App\Repository\AbsencesRepository;
use App\Repository\IntervenantsRepository;
use App\Repository\TableauAbsencesRepository;
use App\Repository\EtudiantsRepository;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/absences")
 */
class AbsencesController extends AbstractController
{
    /**
     * @Route("/", name="app_absences_index", methods={"GET"})
     */
    public function index(AbsencesRepository $absencesRepository, TableauAbsencesRepository $TableauAbsencesRepository): Response
    {
        return $this->render('absences/index.html.twig', [
            'absences' => $absencesRepository->findAll(),
            'tableAbsences' => $TableauAbsencesRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_absences_new", methods={"GET", "POST"})
     */
    public function new(Request $request, AbsencesRepository $absencesRepository, EtudiantsRepository $etudiantsRepository): Response
    {
        $absence = new Absences();
        $form = $this->createForm(AbsencesType::class, $absence);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $absencesRepository->add($absence, true);

            return $this->redirectToRoute('app_absences_index', [], Response::HTTP_SEE_OTHER);
        }
        $etudiant = $etudiantsRepository->findAll();
        return $this->renderForm('absences/new.html.twig', [
            'absence' => $absence,
            'form' => $form,
            'etudiants' => $etudiant,
        ]);
    }
  /**
     * @Route("/new/{id}", name="app_absences_newbyclass", methods={"GET", "POST"})
     */
    public function newbyclasse(Request $request, $id,AbsencesRepository $absencesRepository,etudiantsRepository $etudiantsRepository,TableauAbsencesRepository $TableauAbsencesRepository): Response
    {
      
        $absence = new Absences();
     
        $form = $this->createForm(AbsencesType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
          
            $tableau = $form->get('tableau');
            foreach($tableau as $tableau){
                $tableauabsences = new TableauAbsences();
        

                
                    $TableauAbsencesRepository->add($tableauabsences);

                    $tableauabsences->addAbsence($absence);
                    $absence->addTableau($tableauabsences);
                    

                $etudiants = $tableau->get('etudiant')->getData();
                
                
                $tableauabsences->addEtudiant($etudiants[0]);
                $dateabsence =$tableau->get('dateabsence')->getData();
                $retard = $tableau->get('retard')->getData();
            
                if( $dateabsence == null){
                    $tableauabsences->setDateabsence(null);
                }else{
                    $tableauabsences->setDateabsence($dateabsence);
                }
                if( $retard == null){
                    $tableauabsences->setRetard(null);
                }else{
                    $tableauabsences->setRetard($retard);
                }

   

            }
            $absence->setClasse($form->get('classe')->getData());
            $absence->setDate(new \DateTimeImmutable('now'));
          
      
   
            $absencesRepository->add($absence, true);
  
            // Je boucle sur les documents
           
    

            return $this->redirectToRoute('app_absences_index', [], Response::HTTP_SEE_OTHER);
        }
        $etudiant = $etudiantsRepository->findByclasse($id);

        return $this->renderForm('absences/newByclasse.html.twig', [
            'absences' => $absence,
            'id'=>$id,
            'form' => $form,
            'etudiants' => $etudiant,
          
        ]);
    }
    /**
     * @Route("/{id}", name="app_absences_show", methods={"GET"})
     */
    public function show(Absences $absence): Response
    {
        return $this->render('absences/show.html.twig', [
            'absence' => $absence,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_absences_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Absences $absence, AbsencesRepository $absencesRepository): Response
    {
        $form = $this->createForm(AbsencesType::class, $absence);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $absencesRepository->add($absence, true);

            return $this->redirectToRoute('app_absences_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('absences/edit.html.twig', [
            'absence' => $absence,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_absences_delete", methods={"POST"})
     */
    public function delete(Request $request, Absences $absence, AbsencesRepository $absencesRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$absence->getId(), $request->request->get('_token'))) {
            $absencesRepository->remove($absence, true);
        }

        return $this->redirectToRoute('app_absences_index', [], Response::HTTP_SEE_OTHER);
    }
}
