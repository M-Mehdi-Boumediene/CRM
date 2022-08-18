<?php

namespace App\Controller;

use App\Entity\Users;
use App\Form\UsersType;
use App\Entity\Etudiants;
use App\Entity\Intervenants;
use App\Entity\Tuteurs;
use App\Repository\BlocsRepository;
use App\Repository\UsersRepository;
use App\Repository\ClassesRepository;
use App\Repository\ModulesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/users")
 */
class UsersController extends AbstractController
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * @Route("/", name="app_users_index", methods={"GET"})
     */
    public function index(UsersRepository $usersRepository): Response
    {
        return $this->render('users/index.html.twig', [
            'users' => $usersRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_users_new", methods={"GET", "POST"})
     */
    public function new(Request $request, UsersRepository $usersRepository, EntityManagerInterface $entityManager): Response
    {
        $user = new Users();
        $form = $this->createForm(UsersType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
        
            $plainpwd = $user->getPassword();
            $encoded = $this->passwordEncoder->encodePassword($user,$plainpwd);
            $date = new \DateTimeImmutable('now');
         
            $user->setCreatedBy($this->getUser()->getEmail());
            $user->setUser($user);
            $user->setCreatedAt($date);
            $user->setPassword($encoded);
            $usersRepository->add($user);

            if($form->get('roles')->getData() == ['ROLE_ETUDIANT']){
   
                return $this->redirectToRoute('app_etudiants_new', [], Response::HTTP_SEE_OTHER); 
            }

            if($form->get('roles')->getData() == ['ROLE_INTERVENANT']){
     
                return $this->redirectToRoute('app_intervenants_new', [], Response::HTTP_SEE_OTHER); 
            }

            if($form->get('roles')->getData() == ['ROLE_TUTEUR']){
                $tuteur = new Tuteurs();
                $tuteur->setNom($form->get('nom')->getData());
                $tuteur->setPrenom($form->get('nom')->getData());
                $tuteur->setAdresse($form->get('adresse')->getData());
                $tuteur->setTelephone($form->get('telephone')->getData());
                $tuteur->setEmail($form->get('email')->getData());
                $tuteur->setUsers($user);
                $entityManager->persist($tuteur);
                $entityManager->flush();
                return $this->redirectToRoute('app_tuteurs_new', ['user' => $user,], Response::HTTP_SEE_OTHER); 
            }


         /*   if( $user->getRoles() == ["ROLE_INTERVENANT"])
           { 
                return $this->redirectToRoute('app_intervenants_index', [], Response::HTTP_SEE_OTHER); 
            }
            else if ( $user->getRoles() == ["ROLE_ETUDIANT"])
            {

                return $this->redirectToRoute('app_etudiants_index', [], Response::HTTP_SEE_OTHER); 
            }*/
            
        }

        return $this->renderForm('users/new.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_users_show", methods={"GET"})
     */
    public function show(Users $user,ModulesRepository $modulesRepository,BlocsRepository $blocsRepository ,ClassesRepository $classesRepository,UsersRepository $tuteurRepository,UsersRepository $intervenantsRepository,UsersRepository $etudiantsRepository): Response
    {

        $classe = $user->getClasse();
       /* $bloc =  $$user-->getBlocs();
       /* $module = $classe->getModules();*/

        return $this->render('users/show.html.twig', [
            'user' => $user,
            'classes'=> $classesRepository->findByClasse($classe,$user),
            /*'bloc' => $blocsRepository->findblocByClasse($classe,$bloc),*/
            /*'modules' => $modulesRepository->findModuleByClasse($classe,$module),*/
            'intervenants' => $intervenantsRepository->findByIntervenant(),
            'etudiants' => $etudiantsRepository->findByEtudiant(),
            'tuteurs' => $tuteurRepository->findByTuteur(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_users_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Users $user, UsersRepository $usersRepository): Response
    {
        $form = $this->createForm(UsersType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $plainpwd = $user->getPassword();
            $encoded = $this->passwordEncoder->encodePassword($user,$plainpwd);
            $user->setPassword($encoded);
            $usersRepository->add($user);
            return $this->redirectToRoute('app_users_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('users/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_users_delete", methods={"POST"})
     */
    public function delete(Request $request, Users $user, UsersRepository $usersRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $usersRepository->remove($user);
        }

        return $this->redirectToRoute('app_users_index', [], Response::HTTP_SEE_OTHER);
    }


  /**
     * @Route("/{id}", name="app_users_showparcours", methods={"GET"})
     */
    public function showParcours(Users $user)
    {

        return $this->render('users/parcours.html.twig', [
            'user' => $user]);

    }
}
