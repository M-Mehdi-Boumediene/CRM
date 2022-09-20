<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Form\MessagesType;
use App\Entity\Messages;
use App\Entity\Users;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\UsersRepository;
use App\Repository\MessagesRepository;


class MessagesController extends AbstractController
{
    /**
     * @Route("/messages", name="app_messages")
     */
    public function index(UsersRepository $usersRepository): Response
    {

        return $this->render('messages/index.html.twig', [
            'controller_name' => 'MessagesController',
           
        ]);
    }


 /**
     * @Route("/messages/envoyés", name="app_messages_envoyés")
     */
    public function elements(UsersRepository $usersRepository): Response
    {


        return $this->render('messages/elementsEnvoyer.html.twig', [
            'controller_name' => 'MessagesController',
           
        ]);
    }
 /**
     * @Route("/messages/brouillons", name="app_messages_brouillons")
     */
    public function brouillons(UsersRepository $usersRepository): Response
    {

        return $this->render('messages/brouillons.html.twig', [
            'controller_name' => 'MessagesController',
           
        ]);
    }

     /**
     * @Route("/messages-envoi", name="app_envoi_messages")
     */
    public function envoiMessages(Request $request): Response
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
                $message->setMessage($form->get('message')->getData());
            
            }

            $users = $form->get('users')->getData();

            foreach($users as $users){
               
                $message->setSender($this->getUser());

                $message->addRecipient($users);
        
                $message->addUser($users);
                $message->setObjet($form->get('objet')->getData());
                $message->setMessage($form->get('message')->getData());
            
   
            
            }
               
        
            $em = $this->getDoctrine()->getManager();
            $em->persist($message);
      
            $em->flush();

            $this->addFlash("message","Le message à été envoyé avec succès.");
            $request->getSession()
            ->getFlashBag()
            ->add('message', 'Le message à été envoyé avec succès.');
            
            return $this->redirectToRoute('app_messages');
        }

        return $this->render('messages/envoiMessages.html.twig', [
            'controller_name' => 'MessagesController',
            'form'=> $form->createView(),
        ]);
    }

    /**
     * @Route("/messages/{id}", name="app_read_messages")
     */
    public function readMessage(Request $request,Messages $messager,MessagesRepository $MessagesRepository): Response
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
         
                $message->setRecipient($messager->getSender());
                $message->setObjet($form->get('objet')->getData());
                $message->setMessage($form->get('message')->getData());
                
                $MessagesRepository->add($message, true);

            $this->addFlash("message","Le message à été envoyé avec succès.");
            $request->getSession()
            ->getFlashBag()
            ->add('message', 'Le message à été envoyé avec succès.');
            return $this->redirectToRoute('app_messages');
        }

        return $this->render('messages/readMessage.html.twig', [
            'controller_name' => 'MessagesController',
            'form'=> $form->createView(),
           'message'=> $messager,
        ]);     
    }
}
