<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Form\MessagesType;
use App\Entity\Messages;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\UsersRepository;

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
     * @Route("/messages/{id}", name="app_read_messages")
     */
    public function readMessage(Messages $message): Response
    {
        $message->setIsRead(true);
        $em = $this->getDoctrine()->getManager();
        $em->persist($message);
        $em->flush();

        return $this->render('messages/readMessage.html.twig', compact("message") );
            
    }
}
