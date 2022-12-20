<?php

namespace App\Controller;

use App\Entity\Absences;
use App\Entity\TableauAbsences;
use App\Form\AbsencesType;
use App\Form\filtres\FiltreAbsencesType;
use App\Repository\AbsencesRepository;
use App\Repository\UsersRepository;
use App\Repository\IntervenantsRepository;
use App\Repository\TableauAbsencesRepository;
use App\Repository\EtudiantsRepository;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
/**
 * @Route("/absences")
 */
class AbsencesController extends AbstractController
{
    /**
     * @Route("/", name="app_absences_index", methods={"GET","POST"})
     */
    public function index(request $request, AbsencesRepository $absencesRepository, TableauAbsencesRepository $TableauAbsencesRepository, PaginatorInterface $paginator): Response
    {
        $form2 = $this->createForm(FiltreAbsencesType::class);
        $form2->handleRequest($request);


        if ($form2->isSubmitted() && $form2->isValid()) {
            $value = $form2->get('search')->getData();
   
        $classe = $form2->get('classe')->getData();
        
    
            $tableAbsences =  $TableauAbsencesRepository->searchMot($value,$classe);
            $tableAbsences = $paginator->paginate(
                $tableAbsences, // Requête contenant les données à paginer (ici nos articles)
                $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
                10 // Nombre de résultats par page
            );
       
            return $this->renderForm('absences/index.html.twig', [
                'tableAbsences' => $tableAbsences,
                'form2' => $form2,
            ]);
        }

        
        $tableAbsences =  $TableauAbsencesRepository->findBy(array('presence'=>0));

        $tableAbsences = $paginator->paginate(
            $tableAbsences, // Requête contenant les données à paginer (ici nos articles)
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            10 // Nombre de résultats par page
        );
        return $this->renderForm('absences/index.html.twig', [
            'absences' => $absencesRepository->findAll(),
            'tableAbsences' => $tableAbsences,
            'form2' => $form2,
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
    public function newbyclasse(Request $request, $id,AbsencesRepository $absencesRepository,  UsersRepository $usersRepository, etudiantsRepository $etudiantsRepository,TableauAbsencesRepository $TableauAbsencesRepository): Response
    {
      
        $absence = new Absences();
     
        $form = $this->createForm(AbsencesType::class);
        $form->handleRequest($request);
        $user = $em->getRepository(Users::class)->findOneBy(array('id'=>$etudiant->getUser()));
        if ($form->isSubmitted() && $form->isValid()) {
          
            $tableau = $form->get('tableau');
            foreach($tableau as $tableau){
                $tableauabsences = new TableauAbsences();
        

                
                    $TableauAbsencesRepository->add($tableauabsences);

                    $tableauabsences->addAbsence($absence);
                    $absence->addTableau($tableauabsences);
                    

                $etudiants = $tableau->get('etudiant')->getData();
                $user = $em->getRepository(Users::class)->findOneBy(array('etudiant'=>$etudiants[0]));
                
                $absence->setUserid( $tableau->get('etudiant')->getData());
                $absence->setUser();
                $tableauabsences->addEtudiant($etudiants[0]);
                $dateabsence =$tableau->get('dateabsence')->getData();
                $retard = $tableau->get('retard')->getData();
                $labsence = $tableau->get('absence')->getData();
                if( $dateabsence == null){
                    $tableauabsences->setDateabsence(null);
                    $tableauabsences->setPresence(0);
                }else{
                    
                    $tableauabsences->setDateabsence($dateabsence);
                    $tableauabsences->setPresence(1);
                }
                if( $retard == null){
                    $tableauabsences->setRetard(null);
                    $tableauabsences->setPresence(0);
                }else{
                    $tableauabsences->setRetard($retard);
                    $tableauabsences->setPresence(0);
                }

   

            }
            $absence->setClasse($form->get('classe')->getData());
            $absence->setCalendrier($form->get('calendrier')->getData());
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
