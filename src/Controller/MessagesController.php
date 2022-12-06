<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Form\MessagesType;
use App\Form\SignaturesType;
use App\Entity\Messages;
use App\Entity\Users;
use App\Entity\Files;
use App\Entity\Signatures;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\UsersRepository;
use App\Repository\MessagesRepository;
use App\Repository\ProfilRepository;
use App\Repository\SignaturesRepository;
use App\Repository\SignatureRepository;
use Knp\Component\Pager\PaginatorInterface;

class MessagesController extends AbstractController
{
    /**
     * @Route("/messages", name="app_messages", methods={"GET", "POST"})
     */
    public function index(Request $request,ProfilRepository $profilRepository,  MessagesRepository $messagesRepository,PaginatorInterface $paginator): Response
    {
        
        $user = $this->getUser();
        $messages =  $messagesRepository->findByuser($user);
        $recus =  $messagesRepository->findByrecusisread($user);
        $elements =  $messagesRepository->findBysenderisread($user);
        $brouillons =  $messagesRepository->findBybrouillonisread($user);
        $suppressions =  $messagesRepository->findBysuppisread($user);
        $messages = $paginator->paginate(
            $messages, // Requête contenant les données à paginer (ici nos articles)
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            10 // Nombre de résultats par page
        );
        return $this->render('messages/index.html.twig', [
            'controller_name' => 'MessagesController',
            'messages' =>  $messages,
            'elements' =>  $elements,
            'brouillons' =>  $brouillons,
            'suppressions' =>  $suppressions,
            'recus' =>  $recus,
           
        ]);
    }


 /**
     * @Route("/messages/envoyés", name="app_messages_envoyés", methods={"GET", "POST"})
     */
    public function elements(Request $request,MessagesRepository $messagesRepository, ProfilRepository $profilRepository, PaginatorInterface $paginator): Response
    {
        $user = $this->getUser();
        $messages =  $messagesRepository->findBysender($user);

        $elements =  $messagesRepository->findBysenderisread($user);
        $brouillons =  $messagesRepository->findBybrouillonisread($user);
        $suppressions =  $messagesRepository->findBysuppisread($user);
        $recus =  $messagesRepository->findByrecusisread($user);
        $photoprofil = $profilRepository->findOneBy(array('user'=>$this->getUser()));
        $messages = $paginator->paginate(
            $messages, // Requête contenant les données à paginer (ici nos articles)
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            10 // Nombre de résultats par page
        );
        return $this->render('messages/elementsEnvoyer.html.twig', [
            'controller_name' => 'MessagesController',
            'messages' =>  $messages,
            'photoprofil'=>$photoprofil,
            'elements' =>  $elements,
            'brouillons' =>  $brouillons,
            'suppressions' =>  $suppressions,
            'recus' =>  $recus,
           
        ]);
    }
 /**
     * @Route("/messages/brouillons", name="app_messages_brouillons", methods={"GET", "POST"})
     */
    public function brouillons(Request $request,MessagesRepository $messagesRepository,ProfilRepository $profilRepository,  PaginatorInterface $paginator): Response
    {

        $user = $this->getUser();
         $messages =  $messagesRepository->findBybrouillon($user);
         $elements =  $messagesRepository->findBysenderisread($user);
         $brouillons =  $messagesRepository->findBybrouillonisread($user);
         $recus =  $messagesRepository->findByrecusisread($user);
         $suppressions =  $messagesRepository->findBysuppisread($user);
         $messages = $paginator->paginate(
            $messages, // Requête contenant les données à paginer (ici nos articles)
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            10 // Nombre de résultats par page
        );
        return $this->render('messages/brouillons.html.twig', [
            'controller_name' => 'MessagesController',
            'messages' =>  $messages,
            'elements' =>  $elements,
            'brouillons' =>  $brouillons,
            'suppressions' =>  $suppressions,
            'recus' =>  $recus,
           
        ]);
    }

     /**
     * @Route("/messages/corbeille", name="app_messages_corbeille", methods={"GET", "POST"})
     */
    public function corbeille(Request $request,MessagesRepository $messagesRepository, ProfilRepository $profilRepository, PaginatorInterface $paginator): Response
    {
        $user = $this->getUser();
        $messages =  $messagesRepository->findBysupp($user);
        $elements =  $messagesRepository->findBysenderisread($user);
        $brouillons =  $messagesRepository->findBybrouillonisread($user);
        $recus =  $messagesRepository->findByrecusisread($user);
        $suppressions =  $messagesRepository->findBysuppisread($user);
        $messages = $paginator->paginate(
            $messages, // Requête contenant les données à paginer (ici nos articles)
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            10 // Nombre de résultats par page
        );

        return $this->render('messages/corbeille.html.twig', [
            'controller_name' => 'MessagesController',
            'messages' =>  $messages,
            'elements' =>  $elements,
            'brouillons' =>  $brouillons,
            'suppressions' =>  $suppressions,
            'recus' =>  $recus,
        ]);
    }
      /**
     * @Route("/messages/signature", name="app_messages_signature", methods={"GET", "POST"})
     */
    public function signature(Request $request,SignaturesRepository $signaturesRepository,MessagesRepository $messagesRepository): Response
    {
        $user = $this->getUser();

        $messages =  $messagesRepository->findBysupp($user);
        $elements =  $messagesRepository->findBysenderisread($user);
        $brouillons =  $messagesRepository->findBybrouillonisread($user);
        $recus =  $messagesRepository->findByrecusisread($user);
        $suppressions =  $messagesRepository->findBysuppisread($user);
        $signatures =  $signaturesRepository->findOneBy(array('user'=>$user));


        $signature = new Signatures();
        $form = $this->createForm(SignaturesType::class);
        $form->handleRequest($request);
        if($signature){
            if ($form->isSubmitted() && $form->isValid()) {
                $date = new \DateTimeImmutable('now');
            
                $signaturesRepository->add($signature);
                return $this->redirectToRoute('app_messages_signature', [], Response::HTTP_SEE_OTHER);
            }
        }
 
        


        return $this->renderForm('messages/signature.html.twig', [
            'messages' =>  $messages,
            'elements' =>  $elements,
            'brouillons' =>  $brouillons,
            'suppressions' =>  $suppressions,
            'recus' =>  $recus,
            'signature' =>  $signature,
            'signatures' =>  $signatures,
            'form' =>  $form,
  
        ]);
    }

     /**
     * @Route("/messages-envoi", name="app_envoi_messages")
     */
    public function envoiMessages(Request $request,ProfilRepository $profilRepository): Response
    {
        $message = new Messages;
        $user = new Users;
        $form = $this->createForm(MessagesType::class);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){

            $recipients = $form->get('recipient')->getData();
            foreach($recipients as $recipients){
               
                $message->setSender($this->getUser());
                $message->addRecipient($recipients);
            
                $message->addUser($recipients);
                $message->setObjet($form->get('objet')->getData());
                if($form->get('message')->getData()){
                    $message->setMessage($form->get('message')->getData());
                }else{
                    
                }
           
            
            }

            $users = $form->get('users')->getData();

            foreach($users as $users){
               
                $message->setSender($this->getUser());

                $message->addRecipient($users);
        
                $message->addUser($users);
                $message->setObjet($form->get('objet')->getData());
                $message->setMessage($form->get('message')->getData());
            
   
            
            }

            $files = $form->get('files')->getData();
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
                $file->setName($fichier);
                $file->setNom($fichier);
                $message->addFile($file);

            }
            $message->setBrouillon($form->get('brouillon')->getData());
            $em = $this->getDoctrine()->getManager();
            $em->persist($message);
      
            $em->flush();

            $this->addFlash("message","Le message à été envoyé avec succès.");
            $request->getSession()
            ->getFlashBag()
            ->add('message', 'Le message à été envoyé avec succès.');
            
            return $this->redirectToRoute('app_messages');
        }
        $photoprofil = $profilRepository->findOneBy(array('user'=>$this->getUser()));
        return $this->render('messages/envoiMessages.html.twig', [
            'controller_name' => 'MessagesController',
            'form'=> $form->createView(),
            'photoprofil'=>$photoprofil,
        ]);
    }

    /**
     * @Route("/messages/{id}", name="app_read_messages")
     */
    public function readMessage(Request $request,Messages $messager,MessagesRepository $MessagesRepository, ProfilRepository $profilRepository, ): Response
    {
        $messager->setIsRead(true);
        $em = $this->getDoctrine()->getManager();
        $em->persist($messager);
        $em->flush();
    
        $message = new Messages;
        $form = $this->createForm(MessagesType::class);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $message->setSender($this->getUser());
         
             
                $message->addRecipient($messager->getSender());
                $message->addRecipient($this->getUser());
                $message->setObjet('RE: '.$form->get('objet')->getData());
                $message->setMessage($form->get('message')->getData());
                
                $MessagesRepository->add($message, true);
                $files = $form->get('files')->getData();
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
                    $file->setName($fichier);
                    $file->setNom($fichier);
                    $message->addFile($file);
    
                }
                
       
            $request->getSession()
            ->getFlashBag()
            ->add('message', 'Le message à été envoyé avec succès.');
            return $this->redirectToRoute('app_messages_envoyés');

        }
                
        $user = $this->getUser();
        $suppressions =  $MessagesRepository->findBysuppisread($user);
        $messages =  $MessagesRepository->findByuser($user);
        $elements =  $MessagesRepository->findBysenderisread($user);
        $brouillons =  $MessagesRepository->findBybrouillonisread($user);
        $recus =  $MessagesRepository->findByrecusisread($user);

        $photoprofil = $profilRepository->findOneBy(array('user'=>$this->getUser()));
        return $this->render('messages/readMessage.html.twig', [
            'controller_name' => 'MessagesController',
            'form'=> $form->createView(),
           'message'=> $messager,
           'photoprofil'=>$photoprofil,
           'messages' =>  $messages,
           'elements' =>  $elements,
           'brouillons' =>  $brouillons,
           'suppressions' =>  $suppressions,
           'recus' =>  $recus,
        ]);     
    }


      /**
     * @Route("/messages/corbeil/{id}", name="app_messages_delete", methods={"POST"})
     */
    public function delete(Request $request, Messages $message, MessagesRepository $MessagesRepository): Response
    {
      
        $entityManager = $this->getDoctrine()->getManager();
        $message = $entityManager->getRepository(Messages::class)->find($message);
        $message->setSupprimer(1);
        $entityManager->flush();
        return $this->redirectToRoute('app_messages', [], Response::HTTP_SEE_OTHER);
    }



   

}
