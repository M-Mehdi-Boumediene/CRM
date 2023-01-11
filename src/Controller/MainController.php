<?php

namespace App\Controller;

use App\Repository\CalendrierRepository;
use App\Repository\UsersRepository;
use App\Repository\AbsencesRepository;
use App\Repository\ApprenantsRepository;
use App\Repository\ProfilRepository;
use App\Repository\ClassesRepository;
use App\Entity\Absences;
use App\Entity\TableauAbsences;
use App\Entity\Users;
use App\Form\AbsencesType;
use App\Form\filtres\FiltreCalendrierType;
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

use App\Repository\MessagesRepository;
use App\Repository\EntreprisesRepository;
use App\Repository\BlocsRepository;

use Doctrine\Common\Collections\ArrayCollection;
use App\Entity\Files;



class MainController extends AbstractController
{
    /**
     * @Route("/", name="app_main")
     */
    public function index(EtudiantsRepository $etudiantsRepository,IntervenantsRepository $intervenantRepository,TableauNotesRepository $TableauNotesRepository,EtudiantsRepository $apprenantsRepository,ClassesRepository $classesRepository,EntreprisesRepository $entrprisesRepository,MessagesRepository $messagesRepository,ProfilRepository $profilRepository): Response
    {

        $classesAdmin = $classesRepository->findAll();
        $apprenantsAdmin = $apprenantsRepository->findAll();
        $intervenantsAdmin = $intervenantRepository->findAll();
        $entreprisesAdmin = $entrprisesRepository->findAll();
        $profil = $profilRepository->findOneBy(array('user'=>$this->getUser()));


        $user = $this->getUser();
        $messages =  $messagesRepository->findByuser($user);

     

        $apprenant = $apprenantsRepository->findOneBy(array('user'=>$user));
        $lintervenant = $intervenantRepository->findOneBy(array('user'=>$user));

        $intervenant = $user->getIntervenants();


        $etudiant = $apprenantsRepository->findOneBy(array('user'=>$this->getUser()));
        $tableaunotes = $TableauNotesRepository->findAll();

        $user = $this->getUser();
        $classe = $user->getClasse();
        $etudiant = $etudiantsRepository->findByUser($user);

        $tableaunotes = $TableauNotesRepository->paretudiant1($etudiant);


        $events = $TableauNotesRepository->paretudiant1($etudiant);
        $rdvs = [];
        $rdvs2 = [];
        foreach ($events as $event){
            $rdvs2[] = [
                $event->getModule()->getNom(),
            ];
            foreach ($event->getNotes() as $event){
                $rdvs[] = [
           
                    'name' => $event->getModule()->getNom(),
    
                ];
                foreach ($event->getTableau() as $event){
                    $rdvs[] = [
                        'data' => [$event->getNote1(),$event->getNote2(),$event->getNote3()],
                    
        
                    ];
                }
           
      

             

       
             }
   
          

            $data = json_encode($rdvs);
            $data2 = json_encode($rdvs2);
        }

        foreach ($intervenant as $inter){
          $classe =  $inter->getClasses();
        }
        if( $user->getRoles() == ["ROLE_ENTREPRISE"])
        { 
            return $this->render('main/index.html.twig', [
                'controller_name' => 'MainController',
                        'etudiant' => $etudiant,
            'tableaunotes' => $tableaunotes,
                'apprenantsAdmin' => $apprenantsAdmin,
                'classesAdmin' => $classesAdmin,
                'intervenantsAdmin' => $intervenantsAdmin,
                'entreprisesAdmin' => $entreprisesAdmin,
                'messages' =>  $messages,
                'apprenant' =>  $apprenant,
                'lintervenant'=>$lintervenant, 
                'classes' => $classesRepository->findByIntervenantEtudiant($classe),
                'profil' => $profil,

              
          
            ]);         }else{
                return $this->render('main/index.html.twig', [
                    'controller_name' => 'MainController',
                    'etudiant' => $etudiant,
                    'data' => compact('data'),
                    'data2' => compact('data2'),
                    'tableaunotes' => $tableaunotes,
                    'apprenantsAdmin' => $apprenantsAdmin,
                    'classesAdmin' => $classesAdmin,
                    'intervenantsAdmin' => $intervenantsAdmin,
                    'entreprisesAdmin' => $entreprisesAdmin,
                    'messages' =>  $messages,
                    'apprenant' =>  $apprenant,
                    'lintervenant'=>$lintervenant, 
                    'classes' => $classesRepository->findByIntervenantEtudiant(1),
                    'profil' => $profil,
  
              
                  
              
                ]);
         }
       
    }


    /**
     * @Route("/gestion/calendrier", name="app_gestion_calendrier", methods={"GET", "POST"})
     */
    public function calendrier(Request $request, NotesRepository $notesRepository, FilesRepository $FilesRepository,etudiantsRepository $etudiantsRepository,TableauNotesRepository $TableauNotesRepository, CalendrierRepository $calendrier,EtudiantsRepository $apprenants, AbsencesRepository $absencesRepository, TableauAbsencesRepository $TableauAbsencesRepository, ClassesRepository $classesRepository, IntervenantsRepository $intervenantRepository): Response
    {


        $form2 = $this->createForm(FiltreCalendrierType::class);
        $form2->handleRequest($request);

    

        if ($form2->isSubmitted() && $form2->isValid()) {

       
         $classe = $form2->get('classe')->getData();

         $intervenant = $form2->get('intervenant')->getData();
 

         $events = $calendrier->searchMot($classe,$intervenant);
         $rdvs = [];
         $rdvs2 = [];
         if ($events){
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
                 
                 $retard = $tableau->get('retard')->getData();
                 $presence = $tableau->get('presence')->getData();
                 $enretard = $tableau->get('enretard')->getData();
                 $retard = $tableau->get('retard')->getData();
                 $absencedate = $tableau->get('absence')->getData();

                 $du = $tableau->get('du')->getData();
                 $au = $tableau->get('au')->getData();
                 $tableauabsences->setPresence($presence);
                 $tableauabsences->setEnretard($enretard);
                 $tableauabsences->setRetard($retard);
                 $tableauabsences->setAbsence($absencedate);
                 $tableauabsences->setDu($du);
                 $tableauabsences->setAu($au);
            
                 $absence->setUser($etudiants[0]);
                
 
    
 
             }
             $absence->setClasse($form4->get('classe')->getData());
             $absence->setDate(new \DateTimeImmutable('now'));
     
           
       
    
             $absencesRepository->add($absence, true);
   
             // Je boucle sur les documents
            
     
 
             return $this->redirectToRoute('app_gestion_calendrier', [], Response::HTTP_SEE_OTHER);
         }
 
        
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

             $note = new Notes();
             $tableaunotes->addNote($note);
       
             $note->addTableau($tableaunotes);
             
             $note->setType($form3->get('type')->getData());
             $note->setClasses($form3->get('classes')->getData());
             $note->setModule($form3->get('moduleid')->getData());
             $note->setBloc($form3->get('blocid')->getData());
             $note->setSemestre($form3->get('semestre')->getData());
             $note->setEtudiantid($form3->get('etudiant')->getData());
            
             $etudiants = $tableau->get('etudiant')->getData();
             foreach($etudiants as $etudiants){
             $note->setEtudiantid($etudiants->getId());
             }
             
 
            $etudiants = $tableau->get('etudiant')->getData();
           
                  $tableaunotes->addEtudiant($etudiants[0]);
 
        
  
                 
             $tableaunotes->addEtudiant($form3->get('etudiant')->getData());
  
           
 
             
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
            
     
 
             return $this->redirectToRoute('app_notes_index', [], Response::HTTP_SEE_OTHER);
         }
         $classe = $form2->get('classe')->getData();

         $intervenant = $form2->get('intervenant')->getData();
         $resultclasse = $classesRepository->findOneBy(array('id'=>$classe));
         $resultintervenant = $intervenantRepository->findOneBy(array('id'=>$intervenant));
 
            return $this->renderForm('main/gestion_calendrier.html.twig', [
      
                'etudiants_calendar' => $etudiants,
                'etudiants' => $etudiants,
                'data' => compact('data'),
                'resultclasse' => $resultclasse,
                'resultintervenant'=>$resultintervenant,
                'form2' => $form2,
                'form3' => $form3,
                'form4' => $form4,
                'events'=>$events,
            ]);
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
             $note = new Notes();
             $tableaunotes->addNote($note);
       
             $note->addTableau($tableaunotes);
 
             $note->setType($form3->get('type')->getData());
             $note->setClasses($form3->get('classes')->getData());
             $note->setModule($form3->get('moduleid')->getData());
             $note->setBloc($form3->get('blocid')->getData());
             $note->setSemestre($form3->get('semestre')->getData());
             $etudiants = $tableau->get('etudiant')->getData();
             foreach($etudiants as $etudiants){
             $note->setEtudiantid($etudiants->getId());
             $notesRepository->add($note, true);
             }
        
           
                 
         
  
           
 
             
             $tableaunotes->setNote1($tableau->get('note1')->getData());
             $tableaunotes->setObservation1($tableau->get('observation1')->getData());
          
             $tableaunotes->setNote2($tableau->get('note2')->getData());
             $tableaunotes->setObservation2($tableau->get('observation2')->getData());
 
             $tableaunotes->setNote3($tableau->get('note3')->getData());
             $tableaunotes->setObservation3($tableau->get('observation3')->getData());
 
             $tableaunotes->addCopie($newfile);
             $FilesRepository->add($newfile);
             }
             
           
       
    
             
   
             // Je boucle sur les documents
            
     
 
             return $this->redirectToRoute('app_notes_index', [], Response::HTTP_SEE_OTHER);
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
                 $retard = $tableau->get('retard')->getData();
                 $presence = $tableau->get('presence')->getData();
                 $enretard = $tableau->get('enretard')->getData();
                 $retard = $tableau->get('retard')->getData();
                 $absencedate = $tableau->get('absence')->getData();

                 $du = $tableau->get('du')->getData();
                 $au = $tableau->get('au')->getData();
                 $tableauabsences->setPresence($presence);
                 $tableauabsences->setEnretard($enretard);
                 $tableauabsences->setRetard($retard);
                 $tableauabsences->setAbsence($absencedate);
                 $tableauabsences->setDu($du);
                 $tableauabsences->setAu($au);
 
    
 
             }
             $absence->setClasse($form4->get('classe')->getData());
             $absence->setDate(new \DateTimeImmutable('now'));
           
       
    
             $absencesRepository->add($absence, true);
   
             // Je boucle sur les documents
            
     
 
             return $this->redirectToRoute('app_gestion_calendrier', [], Response::HTTP_SEE_OTHER);
         }

         $events = null;
         $etudiants = null;


         $classe = $form2->get('classe')->getData();

         $intervenant = $form2->get('intervenant')->getData();
         
         
        $resultclasse = $classesRepository->findOneBy(array('id'=>$classe));
        $resultintervenant = $intervenantRepository->findOneBy(array('id'=>$intervenant));

        return $this->renderForm('main/gestion_calendrier.html.twig', [
            'classe'=>$classe,
            'resultclasse' => $resultclasse,
            'resultintervenant'=>$resultintervenant,
            'note' => $note,
            'etudiants'=>$etudiants,
            'form2' => $form2,
            'form3' => $form3,
            'form4' => $form4,
            'events'=>$events,
         
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
    public function calendrierEtudiant(UsersRepository $users,ClassesRepository $classRepository, IntervenantsRepository $intervenantsRepository,Request $request, NotesRepository $notesRepository, FilesRepository $FilesRepository,etudiantsRepository $etudiantsRepository,TableauNotesRepository $TableauNotesRepository, CalendrierRepository $calendrier,EtudiantsRepository $apprenants, AbsencesRepository $absencesRepository, TableauAbsencesRepository $TableauAbsencesRepository ): Response
    {

        $laclasse = $etudiantsRepository->findOneBy(array('email'=>$this->getUser()->getEmail()));

        

        $events = $calendrier->findBy(array('classe'=>$this->getUser()->getClasse()));
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
            
     
           
            
            $classe= $event->getClasse()->getId();
           

        }
        $etudiants = $apprenants->findByClasse($this->getUser()->getClasse());

        $form2 = $this->createForm(FiltreCalendrierType::class);
        $form2->handleRequest($request);

    

    

  



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
             $note = new Notes();
             $tableaunotes->addNote($note);
       
             $note->addTableau($tableaunotes);
 
             $note->setType($form3->get('type')->getData());
             $note->setClasses($form3->get('classes')->getData());
             $note->setModule($form3->get('moduleid')->getData());
             $note->setBloc($form3->get('blocid')->getData());
             $note->setSemestre($form3->get('semestre')->getData());
             $etudiants = $tableau->get('etudiant')->getData();
             foreach($etudiants as $etudiants){
             $note->setEtudiantid($etudiants->getId());
             $notesRepository->add($note, true);
             }
        
           
                 
         
  
           
 
             
             $tableaunotes->setNote1($tableau->get('note1')->getData());
             $tableaunotes->setObservation1($tableau->get('observation1')->getData());
          
             $tableaunotes->setNote2($tableau->get('note2')->getData());
             $tableaunotes->setObservation2($tableau->get('observation2')->getData());
 
             $tableaunotes->setNote3($tableau->get('note3')->getData());
             $tableaunotes->setObservation3($tableau->get('observation3')->getData());
 
             $tableaunotes->addCopie($newfile);
             $FilesRepository->add($newfile);
             }
             
           
       
    
             
   
             // Je boucle sur les documents
            
     
 
             return $this->redirectToRoute('app_notes_index', [], Response::HTTP_SEE_OTHER);
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
                 $retard = $tableau->get('retard')->getData();
                 $presence = $tableau->get('presence')->getData();
                 $enretard = $tableau->get('enretard')->getData();
                 $retard = $tableau->get('retard')->getData();
                 $absencedate = $tableau->get('absence')->getData();

                 $du = $tableau->get('du')->getData();
                 $au = $tableau->get('au')->getData();
                 $tableauabsences->setPresence($presence);
                 $tableauabsences->setEnretard($enretard);
                 $tableauabsences->setRetard($retard);
                 $tableauabsences->setAbsence($absencedate);
                 $tableauabsences->setDu($du);
                 $tableauabsences->setAu($au);
 
    
 
             }
             $absence->setClasse($form4->get('classe')->getData());
             $absence->setDate(new \DateTimeImmutable('now'));
           
       
    
             $absencesRepository->add($absence, true);
   
             // Je boucle sur les documents
            
     
 
             return $this->redirectToRoute('app_gestion_calendrier', [], Response::HTTP_SEE_OTHER);
         }

         if($events){
            $data = json_encode($rdvs);
        }else{
            $data = null;
        }

        return $this->renderForm('main/calendrier_etudiant.html.twig', [
            'etudiants_calendar' => $etudiants,
            'etudiants' => $etudiants,
            'data' => compact('data'),
            'form2' => $form2,
            'form3' => $form3,
            'form4' => $form4,
            'events'=>$events,
          
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
