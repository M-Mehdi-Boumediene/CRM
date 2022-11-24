<?php

namespace App\Controller;

use App\Entity\Absintervenants;
use App\Entity\TableauAbsencesintervenants;

use App\Form\filtres\FiltreAbsintervenantsType;
use App\Form\AbsintervenantsType;
use App\Repository\AbsintervenantsRepository;
use App\Repository\TableauAbsencesRepository;
use App\Repository\TableauAbsencesintervenantsRepository;

use App\Repository\IntervenantsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;

#[Route('/absintervenants')]
class AbsintervenantsController extends AbstractController
{
    /**
     * @Route("/", name="app_absintervenants_index", methods={"GET","POST"})
     */
    public function index(request $request, AbsintervenantsRepository $absintervenantsRepository, TableauAbsencesintervenantsRepository $TableauAbsencesintervenantsRepository, PaginatorInterface $paginator): Response
    {


        $form2 = $this->createForm(FiltreAbsintervenantsType::class);
        $form2->handleRequest($request);


        if ($form2->isSubmitted() && $form2->isValid()) {
            $value = $form2->get('search')->getData();
   
        $classe = $form2->get('classe')->getData();
        

            $tableAbsences =  $TableauAbsencesintervenantsRepository->searchMot($value,$classe);
            $tableAbsences = $paginator->paginate(
                $tableAbsences, // Requête contenant les données à paginer (ici nos articles)
                $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
                10 // Nombre de résultats par page
            );
       
            return $this->renderForm('absencabsences/index.html.twig', [
                'tableAbsences' => $tableAbsences,
                'form2' => $form2,
            ]);
        }

        
        $tableAbsences =  $TableauAbsencesintervenantsRepository->findAll();

        $tableAbsences = $paginator->paginate(
            $tableAbsences, // Requête contenant les données à paginer (ici nos articles)
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            10 // Nombre de résultats par page
        );
        return $this->renderForm('absintervenants/index.html.twig', [
            'absintervenants' => $TableauAbsencesintervenantsRepository->findAll(),
            'tableAbsences' => $tableAbsences,
            'form2' => $form2,
        ]);


    }

    /**
     * @Route("/new", name="app_absintervenants_new", methods={"GET", "POST"})
     */
    public function new(Request $request, AbsintervenantsRepository $absintervenantsRepository, IntervenantsRepository $intervenantsRepository): Response
    {
        $absintervenant = new Absintervenants();
        $form = $this->createForm(AbsintervenantsType::class, $absintervenant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $absintervenantsRepository->add($absintervenant, true);

            return $this->redirectToRoute('app_absintervenants_index', [], Response::HTTP_SEE_OTHER);
        }
        $intervenants = $intervenantsRepository->findAll();
        return $this->renderForm('absintervenants/new.html.twig', [
            'absintervenant' => $absintervenant,
            'intervenants' => $intervenants,
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

  /**
     * @Route("/new/{id}", name="app_absintervenants_newbyclass", methods={"GET", "POST"})
     */
   public function newbyclasse(Request $request, $id,AbsintervenantsRepository $absencesRepository,IntervenantsRepository $IntervenantsRepository,TableauAbsencesintervenantsRepository $TableauAbsencesRepository): Response
   {
     
       $absence = new Absintervenants();
    
       $form = $this->createForm(AbsintervenantsType::class);
       $form->handleRequest($request);

       if ($form->isSubmitted() && $form->isValid()) {
         
           $tableau = $form->get('tableauAbsencesintervenants');
           foreach($tableau as $tableau){
               $tableauabsences = new TableauAbsencesintervenants();
       

               
                   $TableauAbsencesRepository->add($tableauabsences);

                   $tableauabsences->addAbsence($absence);
                   $absence->addTableauAbsencesintervenant($tableauabsences);
                   

               $etudiants = $tableau->get('intervenant')->getData();
                
               $tableauabsences->addIntervenant($etudiants[0]);
               $absence->setUserid($tableau->get('userid')->getData());
               
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
          
   

           return $this->redirectToRoute('app_absintervenants_index', [], Response::HTTP_SEE_OTHER);
       }
       $etudiant = $IntervenantsRepository->findByclasse($id);

       return $this->renderForm('absintervenants/newByclasse.html.twig', [
           'absences' => $absence,
           'id'=>$id,
           'form' => $form,
           'intervenants' => $etudiant,
         
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
