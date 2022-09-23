<?php

namespace App\Controller;

use App\Repository\CalendrierRepository;
use App\Repository\UsersRepository;
use App\Repository\AbsencesRepository;
use App\Repository\ApprenantsRepository;
use App\Entity\Absences;
use App\Entity\TableauAbsences;
use App\Entity\Users;
use App\Form\AbsencesType;
use App\Form\FiltreCalendrierType;
use App\Repository\EtudiantsRepository;
use App\Repository\TableauAbsencesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Notes;
use App\Entity\TableauNotes;
use App\Form\NotesType;
use App\Form\FiltreNotesType;
use App\Repository\NotesRepository;
use App\Repository\IntervenantsRepository;
use App\Repository\TableauNotesRepository;
use App\Repository\FilesRepository;
use App\Repository\ModulesRepository;
use App\Repository\BlocsRepository;

use Doctrine\Common\Collections\ArrayCollection;
use App\Entity\Files;



class MainController extends AbstractController
{
    /**
     * @Route("/", name="app_main")
     */
    public function index(): Response
    {
        return $this->render('main/index.html.twig', [
            'controller_name' => 'MainController',
        ]);
    }


    /**
     * @Route("/gestion/calendrier", name="app_gestion_calendrier", methods={"GET", "POST"})
     */
    public function calendrier(Request $request, NotesRepository $notesRepository, FilesRepository $FilesRepository,etudiantsRepository $etudiantsRepository,TableauNotesRepository $TableauNotesRepository, CalendrierRepository $calendrier,EtudiantsRepository $apprenants, AbsencesRepository $absencesRepository, TableauAbsencesRepository $TableauAbsencesRepository): Response
    {


        $form2 = $this->createForm(FiltreCalendrierType::class);
        $form2->handleRequest($request);

    

        if ($form2->isSubmitted() && $form2->isValid()) {

       
         $classe = $form2->get('classe')->getData();

         $intervenant = $form2->get('intervenant')->getData();
         $apprenant = $form2->get('apprenant')->getData();

         if($classe == null){
            $classe = empty($classe);
        }
        if($intervenant == null){
            $intervenant = empty($intervenant);
        }
        if($apprenant == null){
            $apprenant = empty($apprenant);
        }

         $events = $calendrier->searchMot($classe,$intervenant,$apprenant);
         $rdvs = [];
         $rdvs2 = [];
         foreach ($events as $event){
             
             $rdvs[] = [
                 'id' => $event->getId(),
                 'start' => $event->getStart()->format('Y-m-d H:i'),
                 'end' => $event->getEnd()->format('Y-m-d H:i'),
                 'backgroundColor' => $event->getBackgroundColor(),
                 'borderColor' => $event->getBackgroundColor(),
                 'textColor' => $event->getTextColor(),
                 'title' => $event->getTitre(),
                 'description' => $event->getDescription(),
                 'classe' => $event->getClasse()->getNom(),
              
                 'module' => $event->getModule()->getNom(),
                 'intervenant' => $event->getIntervenant()->getNom(),
                 'textColor' => $event->getTextColor(),
                 'allDay' => $event->getAllDay(),
                 'type' => $event->getType(),
 
 
             ];
 
 
             
             $classe= $event->getClasse()->getId();
 
 
             $data = json_encode($rdvs);
      
             
         }
         
         if($events){
            $etudiants = $apprenants->findByClasse($classe);
         }else{
            $etudiants = null;
            $data = null;
         }
       
         $absence = new Absences();
     
         $form4 = $this->createForm(AbsencesType::class);
         $form4->handleRequest($request);
 
         if ($form4->isSubmitted() && $form4->isValid()) {
           
             $tableau = $form4->get('tableau');
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
             $absence->setClasse($form4->get('classe')->getData());
             $absence->setDate(new \DateTimeImmutable('now'));
           
       
    
             $absencesRepository->add($absence, true);
   
             // Je boucle sur les documents
            
     
 
             return $this->redirectToRoute('app_gestion_calendrier', [], Response::HTTP_SEE_OTHER);
         }
 
         $note = new Notes();
     
         $form3 = $this->createForm(NotesType::class);
         $form3->handleRequest($request);
 
         if ($form3->isSubmitted() && $form3->isValid()) {
           
             $tableau = $form3->get('tableau');
             foreach($tableau as $tableau){
                 $tableaunotes = new TableauNotes();
                 $newfile= new Files();
             $files = $tableau->get('copie')->getData();
 
             foreach($files as $file){
                 $em = $this->getDoctrine()->getManager();
                 // Je génère un nouveau nom de fichier
                 $fichier = md5(uniqid()) . '.' . $file->guessExtension();
 
                 // Je copie le fichier dans le dossier uploads
                 $file->move(
                     $this->getParameter('videos_directory'),
                     $fichier
                 );
 
                 // Je stocke le document dans la BDD (nom du fichier)
               
                 $date = new \DateTimeImmutable('now');
                 $newfile->setName($fichier);
                 $newfile->setTableauNotes($tableaunotes);
                 $newfile->setNom('Copie');
 
                
  
             }
             $TableauNotesRepository->add($tableaunotes);
 
             $tableaunotes->addNote($note);
             $note->addTableau($tableaunotes);
             
             $note->setType($form3->get('type')->getData());
             $note->setClasses($form3->get('classes')->getData());
             $note->setModule($form3->get('moduleid')->getData());
             $note->setBloc($form3->get('blocid')->getData());
 

             
            $etudiants = $tableau->get('etudiant')->getData();
           
                 
             $tableaunotes->addEtudiant($etudiants[0]);
  
       
             $tableaunotes->setNote1($tableau->get('note1')->getData());
             $tableaunotes->setObservation1($tableau->get('observation1')->getData());
          
             $tableaunotes->setNote2($tableau->get('note2')->getData());
             $tableaunotes->setObservation2($tableau->get('observation2')->getData());
 
             $tableaunotes->setNote3($tableau->get('note3')->getData());
             $tableaunotes->setObservation3($tableau->get('observation3')->getData());
 
             $tableaunotes->addCopie($newfile);
             $FilesRepository->add($newfile);
             }
             
           
       
    
             $notesRepository->add($note, true);
   
             // Je boucle sur les documents
            
     
 
             return $this->redirectToRoute('app_gestion_calendrier', [], Response::HTTP_SEE_OTHER);
         }
         

            return $this->renderForm('main/gestion_calendrier.html.twig', [
      
                'etudiants_calendar' => $etudiants,
                'etudiants' => $etudiants,
                'data' => compact('data'),
                'form2' => $form2,
                'form3' => $form3,
                'form4' => $form4,
                'events'=>$events,
            ]);
        }

        $events = $calendrier->findAll();
        $rdvs = [];
        $rdvs2 = [];
        foreach ($events as $event){
            
            $rdvs[] = [
                'id' => $event->getId(),
                'start' => $event->getStart()->format('Y-m-d H:i'),
                'end' => $event->getEnd()->format('Y-m-d H:i'),
                'backgroundColor' => $event->getBackgroundColor(),
                'borderColor' => $event->getBackgroundColor(),
                'textColor' => $event->getTextColor(),
                'title' => $event->getTitre(),
                'description' => $event->getDescription(),
                'classe' => $event->getClasse()->getNom(),
             
                'module' => $event->getModule()->getNom(),
                'intervenant' => $event->getIntervenant()->getNom(),
                'textColor' => $event->getTextColor(),
                'allDay' => $event->getAllDay(),
                'type' => $event->getType(),


            ];


            
            $classe= $event->getClasse()->getId();


            $data = json_encode($rdvs);
     
            
        }


  
        $etudiants = $apprenants->findByClasse($classe);

         $events= null;




         $note = new Notes();
     
         $form3 = $this->createForm(NotesType::class);
         $form3->handleRequest($request);
 
         if ($form3->isSubmitted() && $form3->isValid()) {
           
             $tableau = $form3->get('tableau');
             foreach($tableau as $tableau){
                 $tableaunotes = new TableauNotes();
                 $newfile= new Files();
             $files = $tableau->get('copie')->getData();
 
             foreach($files as $file){
                 $em = $this->getDoctrine()->getManager();
                 // Je génère un nouveau nom de fichier
                 $fichier = md5(uniqid()) . '.' . $file->guessExtension();
 
                 // Je copie le fichier dans le dossier uploads
                 $file->move(
                     $this->getParameter('videos_directory'),
                     $fichier
                 );
 
                 // Je stocke le document dans la BDD (nom du fichier)
               
                 $date = new \DateTimeImmutable('now');
                 $newfile->setName($fichier);
                 $newfile->setTableauNotes($tableaunotes);
                 $newfile->setNom('Copie');
 
                
  
             }
             $TableauNotesRepository->add($tableaunotes);
 
             $tableaunotes->addNote($note);
             $note->addTableau($tableaunotes);
             
             $note->setType($form3->get('type')->getData());
             $note->setClasses($form3->get('classes')->getData());
             $note->setModule($form3->get('moduleid')->getData());
             $note->setBloc($form3->get('blocid')->getData());
 

             
            $etudiants = $tableau->get('etudiant')->getData();
           
                 
             $tableaunotes->addEtudiant($etudiants[0]);
  
       
             $tableaunotes->setNote1($tableau->get('note1')->getData());
             $tableaunotes->setObservation1($tableau->get('observation1')->getData());
          
             $tableaunotes->setNote2($tableau->get('note2')->getData());
             $tableaunotes->setObservation2($tableau->get('observation2')->getData());
 
             $tableaunotes->setNote3($tableau->get('note3')->getData());
             $tableaunotes->setObservation3($tableau->get('observation3')->getData());
 
             $tableaunotes->addCopie($newfile);
             $FilesRepository->add($newfile);
             }
             
           
       
    
             $notesRepository->add($note, true);
   
             // Je boucle sur les documents
            
     
 
             return $this->redirectToRoute('app_gestion_calendrier', [], Response::HTTP_SEE_OTHER);
         }
         


         $absence = new Absences();
     
         $form4 = $this->createForm(AbsencesType::class);
         $form4->handleRequest($request);
 
         if ($form4->isSubmitted() && $form4->isValid()) {
           
             $tableau = $form4->get('tableau');
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
             $absence->setClasse($form4->get('classe')->getData());
             $absence->setDate(new \DateTimeImmutable('now'));
           
       
    
             $absencesRepository->add($absence, true);
   
             // Je boucle sur les documents
            
     
 
             return $this->redirectToRoute('app_gestion_calendrier', [], Response::HTTP_SEE_OTHER);
         }
         $id = $classe;
         $etudiant = $etudiantsRepository->findByclasse($id);

        return $this->renderForm('main/gestion_calendrier.html.twig', [
            'etudiants_calendar' => $etudiants,
            'data' => compact('data'),
            'note' => $note,
            'id'=>$id,
            'form2' => $form2,
            'form3' => $form3,
            'form4' => $form4,
            'events'=>$events,
            'etudiants' => $etudiants,
        ]
    
    );
    }


    
     /**
     * @Route("/calendrier_absences", name="app_gestion_calendrier_absences", methods={"GET", "POST"})
     */
    public function calendrierAbsences( EntityManagerInterface $em, Request $request): Response
    {
        $date = new \DateTimeImmutable('now');
        $etat = $request->query->get('etat');
        $user = $request->query->get('user');

 
        $sql = "INSERT INTO `absences` (`id`, `module_id`, `date`, `created_at`, `created_by`, `du`, `au`, `classe_id`, `absent`, `dateabsence`, `enretard`, `dateretard`, `present`, `datepresence`, `userid`, `user_id`) VALUES (NULL, '6', '2021-09-26 16:43:54', '2022-04-04 10:37:26', NULL, '2022-06-07 16:40:41', '2022-06-07 16:40:41', '2', '1', '2022-06-07 16:48:04', '1', '2022-06-07 16:48:04', '1', '2022-06-07 16:48:04', '1', $user)";
        $stmt = $em->getConnection()->prepare($sql);
     
        $result = $stmt->execute();

        // returns an array of Product objects  
        $response = new JsonResponse();
        $response->setContent(json_encode($etat));
        $response->headers->set('Content-Type','application/json');

        return $response->setData(array('etat'=>$etat,'user'=>$user));

    
  
    }

    /**
     * @Route("/calendrier_etudiant", name="app_calendrier_etudiant")
     */
    public function calendrierEtudiant(CalendrierRepository $calendrier,UsersRepository $users, EtudiantsRepository $apprenants ): Response
    {

        $events = $calendrier->findAll();
        $rdvs = [];
        foreach ($events as $event){

            $rdvs[] = [
                'id' => $event->getId(),
                'start' => $event->getStart()->format('Y-m-d H:i'),
                'end' => $event->getEnd()->format('Y-m-d H:i'),
                'backgroundColor' => $event->getBackgroundColor(),
                'borderColor' => $event->getBackgroundColor(),
                'textColor' => $event->getTextColor(),
                'title' => $event->getTitre(),
                'description' => $event->getDescription(),
                'classe' => $event->getClasse()->getNom(),
                'module' => $event->getModule()->getNom(),
                'intervenant' => $event->getIntervenant()->getNom(),
                'textColor' => $event->getTextColor(),
                'allDay' => $event->getAllDay(),
                'type' => $event->getType(),


            ];

            $data = json_encode($rdvs);
            
            $classe= $event->getClasse()->getId();
        }
        $etudiants = $apprenants->findByClasse($classe);

        return $this->render('main/calendrier_etudiant.html.twig', [
            'etudiants_calendar' => $etudiants,
            'data' => compact('data'),
          
        ]
    
    );
 
    }

     /**
     * @Route("/calendrier_intervenant", name="app_calendrier_intervenant")
     */
    public function calendrierIntervenant(CalendrierRepository $calendrier): Response
    {
        $events = $calendrier->findAll();
        $rdvs = [];
        foreach ($events as $event){

            $rdvs[] = [
                'id' => $event->getId(),
                'start' => $event->getStart()->format('Y-m-d H:i'),
                'end' => $event->getEnd()->format('Y-m-d H:i'),
                'backgroundColor' => $event->getBackgroundColor(),
                'borderColor' => $event->getBackgroundColor(),
                'textColor' => $event->getTextColor(),
                'title' => $event->getTitre(),
                'description' => $event->getDescription(),
                'textColor' => $event->getTextColor(),
                'allDay' => $event->getAllDay(),
                'type' => $event->getType(),


            ];

            $data = json_encode($rdvs);
        }
        return $this->render('main/calendrier_intervenant.html.twig',compact('data'));
    }
}
