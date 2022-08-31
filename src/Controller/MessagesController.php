<?php

namespace App\Controller;

use App\Entity\Users;
use App\Entity\Messages;
use App\Form\MessagesType;
use App\Form\ReponseMessageType;
use App\Repository\UsersRepository;
use App\Repository\MessagesRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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
     * @Route("/messages-envoi", name="app_envoi_messages")
     */
    public function envoiMessages(Request $request): Response
    {
        $message = new Messages;
        $form = $this->createForm(MessagesType::class, $message);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $message->setSender($this->getUser());
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
     * @Route("/messages-repond/{objet}/{recipient}", name="app_repond_messages")
     */
    public function repondMessages(Request $request,String $objet, $recipient): Response
    {
      //  $utilisateur 
      $recipient = "Adiba";
        $message = new Messages;
        $form = $this->createForm(ReponseMessageType::class, $message);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $message->setSender($this->getUser());
         //   $message->setRecipient($utilisateur);
            $em = $this->getDoctrine()->getManager();
            $em->persist($message);
            $em->flush();

            $this->addFlash("message","Le message à été envoyé avec succès.");
            $request->getSession()
            ->getFlashBag()
            ->add('message', 'Le message à été envoyé avec succès.');
            return $this->redirectToRoute('app_messages');
        }

        return $this->render('messages/reponseMessages.html.twig', [
            'controller_name' => 'MessagesController',
            'form'=> $form->createView(),
            'objet'=> $objet,
            'recipient'=> $recipient,
        ]);
    }

    /**
     * @Route("/messages/{id}", name="app_read_messages")
     */
    public function readMessage(Messages $message): Response
    {
        $message->setIsRead(true);
        $em = $this->getDoctrine()->getManager();
        $em->persist($message);
        $em->flush();

        return $this->render('messages/readMessage.html.twig',  compact("message"));
                
    }

    /**
     * @Route("/messages-sent", name="sent")
     */
    public function sent(): Response
    {
        $message = new Messages;
            return $this->render('messages/messageEnvoyé.html.twig', compact("message"));
    }



     /**
     * @Route("/archiver/{id}", name="archiver")
     */
    public function archiver(Messages $message): Response
    {
        $message->setRemoveMsg(true);
        $em = $this->getDoctrine()->getManager();
        $em->persist($message);
        $em->flush();
     
             return $this->render('messages/index.html.twig');
    }



    /**
     * @Route("/corbeille", name="corbeille")
     */
   public function Messagesarchived(MessagesRepository $messagesRepository): Response
   {
           
             $message2=$messagesRepository->archive();

           return $this->render('messages/corbeille.html.twig',[ 'message2'=>$message2]);
   }



    /**
     * @Route("/delete/{id}", name="delete")
     */
    public function delete(Messages $message): Response
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($message);
        $em->flush();

        return $this->redirectToRoute("sent");
    }
}
