<?php

namespace App\Controller;

use App\Entity\Etudiants;
use App\Entity\Notes;
use App\Entity\Notifications;
use App\Entity\TableauNotes;
use App\Form\NotesType;
use App\Form\filtres\FiltreNotesType;
use App\Repository\NotesRepository;
use App\Repository\IntervenantsRepository;
use App\Repository\TableauNotesRepository;
use App\Repository\FilesRepository;
use App\Repository\EtudiantsRepository;
use App\Repository\ModulesRepository;
use App\Repository\NotificationsRepository;
use App\Repository\BlocsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Common\Collections\ArrayCollection;
use App\Entity\Files;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;

use Doctrine\ORM\Query\Expr\Join;
/**
 * @Route("/notes")
 */
class NotesController extends AbstractController
{
    /**
     * @Route("/", name="app_notes_index", methods={"GET","POST"})
     */
    public function index(Request $request,NotesRepository $notesRepository,TableauNotesRepository $TableauNotesRepository, PaginatorInterface $paginator): Response
    {
        $form2 = $this->createForm(FiltreNotesType::class);
        $form2->handleRequest($request);


        if ($form2->isSubmitted() && $form2->isValid()) {
            $value = $form2->get('search')->getData();
            $apprenant = $form2->get('etudiant')->getData();
        $classe = $form2->get('classe')->getData();
        $module = $form2->get('modules')->getData();
        
      
       
        $tableNotes =  $TableauNotesRepository->searchMot($value,$classe,$module,$apprenant);

        $tableNotes = $paginator->paginate(
            $tableNotes, // Requête contenant les données à paginer (ici nos articles)
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            3 // Nombre de résultats par page
        );
            return $this->renderForm('notes/index.html.twig', [
                'tableNotes' => $tableNotes,
                'form2' => $form2,
            ]);
        }
        $tableNotes =  $TableauNotesRepository->findAll();
        $tableNotes = $paginator->paginate(
            $tableNotes, // Requête contenant les données à paginer (ici nos articles)
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            10 // Nombre de résultats par page
        );
        return $this->renderForm('notes/index.html.twig', [
            'notes' => $notesRepository->findAll(),
            'tableNotes' => $tableNotes,
            'form2' => $form2,
        ]);
    }

    /**
     * @Route("/new", name="app_notes_new", methods={"GET", "POST"})
     */
    public function new(Request $request, NotesRepository $notesRepository, etudiantsRepository $etudiantsRepository): Response
    {
        $note = new Notes();
        $form = $this->createForm(NotesType::class, $note);
        $form->handleRequest($request);
/*
        if($form->get('classes')->getData()){
            $classe = $form->get('classes')->getData();
            $etudiant = $etudiantsRepository->findByClasse($classe);
            return $this->renderForm('notes/new.html.twig', [
                'note' => $note,
                'form' => $form,
                'etudiants' => $etudiant,
              
            ]);
        }

       */

        if ($form->isSubmitted() && $form->isValid()) {

       
            $notesRepository->add($note, true);

            return $this->redirectToRoute('app_notes_index', [], Response::HTTP_SEE_OTHER);
        }
        $etudiant = $etudiantsRepository->findAll();

        return $this->renderForm('notes/new.html.twig', [
            'note' => $note,
            'form' => $form,
            'etudiants' => $etudiant,
          
        ]);
    }

    /**
     * @Route("/new/{id}", name="app_notes_newbyclass", methods={"GET", "POST"})
     */
    public function newbyclasse(Request $request,EntityManagerInterface $em, $id,NotesRepository $notesRepository, NotificationsRepository $notificationsRepository, FilesRepository $FilesRepository,etudiantsRepository $etudiantsRepository,TableauNotesRepository $TableauNotesRepository): Response
    {
      
        $note = new Notes();
     
        $form = $this->createForm(NotesType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
           
            $tableau = $form->get('tableau');
          
           
            foreach($tableau as $tableau){
                $tableaunotes = new TableauNotes();
                $tableaunotes->setCreatedBy($this->getUser()->getEmail());
                $date = new \DateTimeImmutable('now');
                $tableaunotes->setCreatedAt($date);
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
              
            
                $newfile->setName($fichier);
                $newfile->setTableauNotes($tableaunotes);
                $newfile->setNom('Copie');

               
 
            }
          
            $TableauNotesRepository->add($tableaunotes);

            $note = new Notes();
        
      
            $note->addTableau($tableaunotes);
            $date = new \DateTimeImmutable('now');
            $note->setCreatedBy($this->getUser()->getEmail());
    
            $note->getCreatedAt($date);
            $note->setType($form->get('type')->getData());
            $note->setClasses($form->get('classes')->getData());
            $note->setModule($form->get('module')->getData());
    
            $note->setBloc($form->get('blocid')->getData());
            $note->setSemestre($form->get('semestre')->getData());
            $etudiants = $tableau->get('etudiant')->getData();
            foreach($etudiants as $etudiants){
            $note->setEtudiantid($etudiants->getId());
            $ee = $etudiants->getId();


            $lemodule = $form->get('module')->getData();
            $lesemestre = $form->get('semestre')->getData();
            $letype= $form->get('type')->getData();
            $latable = $TableauNotesRepository->findByetudiant($ee,$lemodule,$lesemestre,$letype);



            $notification = new Notifications();

            $notification->setTitre("Nouvelle note");
            $notification->setDescription("Vous avez eu une nouvelle note veuilez consulter vos notes");
            $notification->setEtudiant($etudiants->getId());

        }
       
            
          

            if($latable){

                return $this->redirectToRoute('app_notes_index', [], Response::HTTP_SEE_OTHER);
            }
            else{
                
                $tableaunotes->addNote($note);
                $tableaunotes->addEtudiant($etudiants);
     
              
    
                
                $tableaunotes->setNote1($tableau->get('note1')->getData());
                $tableaunotes->setObservation1($tableau->get('observation1')->getData());
             
                $tableaunotes->setNote2($tableau->get('note2')->getData());
                $tableaunotes->setObservation2($tableau->get('observation2')->getData());
    
                $tableaunotes->setNote3($tableau->get('note3')->getData());
                $tableaunotes->setObservation3($tableau->get('observation3')->getData());
    
                $tableaunotes->addCopie($newfile);
    
    
                $FilesRepository->add($newfile);
            }
    


                
            
            }
            
          

      
   
            $notesRepository->add($note, true);
             $notificationsRepository->add($notification, true);
            // Je boucle sur les documents
           
    

            return $this->redirectToRoute('app_notes_index', [], Response::HTTP_SEE_OTHER);
        }
        $etudiant = $etudiantsRepository->findByclasse($id);

        return $this->renderForm('notes/newByclasse.html.twig', [
            'note' => $note,
            'id'=>$id,
            'form' => $form,
            'etudiants' => $etudiant,
          
        ]);
    }

    
    /**
     * @Route("/gestion", name="app_notes_gestion", methods={"GET", "POST"})
     */
    public function gestionnotes(Request $request, NotesRepository $notesRepository,intervenantsRepository $intervenantsRepository,etudiantsRepository $etudiantsRepository): Response
    {
        $note = new Notes();
        $form = $this->createForm(NotesType::class, $note);
        $form->handleRequest($request);
        $user = $this->getUser();
        $class= $this->getUser()->getClasse();
        $intervenant = $user->getIntervenants();

        foreach ($intervenant as $inter){
          $classe =  $inter->getClasses();
        }


        $etudiant = $etudiantsRepository->findByClasse(1);
       
        return $this->renderForm('notes/gestion.html.twig', [
            'etudiants' => $etudiant,
            'note' => $note,
            'form' => $form,
        ]);
    }


 /**
     * @Route("/calendrier_absences", name="user_notes", methods={"GET", "POST"})
     */
    public function userNotes( EntityManagerInterface $em, IntervenantsRepository $intervenantsRepository, EtudiantsRepository $etudiantsRepository, ModulesRepository $modulesRepository, BlocsRepository $blocsRepository, Request $request): Response
    {
        $date = date('Y-m-d H:i:s');
        $note = $request->query->get('note');
        $etud = $request->query->get('user');
        $type =  $request->query->get('type');
        $lemodule =  $request->query->get('module');

        $user = $this->getUser();
        $etudiant = $etudiantsRepository->findOneBy(array('user'=>$user));
        $intervenant = $intervenantsRepository->findOneBy(array('user'=>$user));
        $ap= $intervenant;
        

        $apprenant = $request->query->get('user');
        $mod = $request->query->get('module');
        $bloc = $request->query->get('blocid');
        $module = $modulesRepository->findOneBy(array('nom'=>$mod));
        $blocid = $blocsRepository->findOneBy(array('nom'=>$bloc));

        $module_id = $module->getId();

        /* calculer la moyene */
        $coefficient = $module->getCoefficient();

        $moyenne = ($note * $coefficient) / $coefficient;
  
    $sql = "INSERT INTO `notes` (`id`,`note`, `moduleid`, `etudiantid`, `intervenantid`, `type`, `moyenne`, `module_id`, `blocid`) VALUES (null,'$note','$lemodule','$etud','$ap','$type','$moyenne','$module_id','$blocid')";
    $stmt = $em->getConnection()->prepare($sql);
 
    $result = $stmt->execute();

        // returns an array of Product objects  
        $response = new JsonResponse();
        $response->setContent(json_encode($note));
        $response->headers->set('Content-Type','application/json');

        return $response->setData(array('note'=>$note,'user'=>$user));

    
  
    }

    /**
     * @Route("/{id}", name="app_notes_show", methods={"GET"})
     */
    public function show(Notes $note): Response
    {
        return $this->render('notes/show.html.twig', [
            'note' => $note,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_notes_edit", methods={"GET", "POST"})
     */
    public function edit($id,Request $request, Notes $note, NotesRepository $notesRepository, etudiantsRepository $etudiantsRepository,EntityManagerInterface $entityManager): Response
    {

        if (null === $task = $entityManager->getRepository(Notes::class)->find($note)) {
            throw $this->createNotFoundException('No task found for id '.$note);
        }
        $originalTags = new ArrayCollection();

        foreach ($task->getTableau() as $tag) {
            $originalTags->add($tag);
        }

        $form = $this->createForm(NotesType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
              foreach ($originalTags as $tag) {
                if (false === $task ->getTableau()->contains($tag)) {
                    // remove the Task from the Tag
                    $tag->getNotes()->removeElement($task);

                    // if it was a many-to-one relationship, remove the relationship like this
                    // $tag->setTask(null);

                    $entityManager->persist($tag);

                    // if you wanted to delete the Tag entirely, you can also do that
                    // $entityManager->remove($tag);
                }

                $entityManager->persist($task);
                $entityManager->flush();
            }

       

            return $this->redirectToRoute('app_notes_edit', ['id' => $id]);
        }
        $etudiant = $etudiantsRepository->findByClasse(1);
        return $this->renderForm('notes/edit.html.twig', [
            'note' => $note,
            'form' => $form,
              'etudiants' => $etudiant,
          
        ]);
    }

    /**
     * @Route("/{id}", name="app_notes_delete", methods={"POST"})
     */
    public function delete(Request $request, Notes $note, NotesRepository $notesRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$note->getId(), $request->request->get('_token'))) {
            $notesRepository->remove($note, true);
        }

        return $this->redirectToRoute('app_notes_index', [], Response::HTTP_SEE_OTHER);
    }
}