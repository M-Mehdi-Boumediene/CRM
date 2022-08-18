<?php

namespace App\Controller;

use App\Entity\Intervenants;
use App\Entity\Users;
use App\Form\UsersType;
use App\Form\IntervenantsType;
use App\Repository\IntervenantsRepository;
use App\Repository\ModulesRepository;
use App\Repository\UsersRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/intervenants")
 */
class IntervenantsController extends AbstractController
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }
    /**
     * @Route("/", name="app_intervenants_index", methods={"GET"})
     */
    public function index(IntervenantsRepository $intervenantRepository): Response
    {
     
        return $this->render('intervenants/index.html.twig', [
            'intervenants' => $intervenantRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_intervenants_new", methods={"GET", "POST"})
     */
    public function new(Request $request, IntervenantsRepository $intervenantsRepository, UsersRepository $usersRepository, EntityManagerInterface $entityManager, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $intervenant = new Intervenants();
        $user = new Users();
        $form = $this->createForm(IntervenantsType::class, $intervenant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $date = new \DateTimeImmutable('now');
         
            $intervenant->setCreatedBy($this->getUser()->getEmail());
            $intervenant->setUser($this->getUser());
            $intervenant->setCreatedAt($date);
            $intervenant->setEmail($form->get('user')->get('email')->getData());
            $intervenant->setVille($form->get('ville')->getData());
            $intervenantsRepository->add($intervenant);

      
            $password = $passwordEncoder->encodePassword($user, $form->get('user')->get('password')->getData());
            $user->setPassword($password);
          
            $date = new \DateTimeImmutable('now');
         
            $user->setCreatedBy($this->getUser()->getEmail());
            $user->setUser($user);
            $user->setEmail($form->get('user')->get('email')->getData());
            $user->setRoles(['ROLE_INTERVENANT']);
            $user->setCreatedAt($date);
            $user->setPassword($password);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
     
            return $this->redirectToRoute('app_intervenants_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('intervenants/new.html.twig', [
            'intervenant' => $intervenant,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_intervenants_show", methods={"GET"})
     */
    public function show(Intervenants $intervenant,ModulesRepository $modulesRepository): Response
    {

        $id = $intervenant->getClasses()->getId();
    
        return $this->render('intervenants/show.html.twig', [
            'modules' => $modulesRepository->findByClasse($id),
            'intervenant' => $intervenant,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_intervenants_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Intervenants $intervenant, IntervenantsRepository $intervenantsRepository): Response
    {
        $form = $this->createForm(IntervenantsType::class, $intervenant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $intervenantsRepository->add($intervenant);
            return $this->redirectToRoute('app_intervenants_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('intervenants/edit.html.twig', [
            'intervenant' => $intervenant,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_intervenants_delete", methods={"POST"})
     */
    public function delete(Request $request, Intervenants $intervenant, IntervenantsRepository $intervenantsRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$intervenant->getId(), $request->request->get('_token'))) {
            $intervenantsRepository->remove($intervenant);
        }

        return $this->redirectToRoute('app_intervenants_index', [], Response::HTTP_SEE_OTHER);
    }



    /**
     * @Route("/{id}", name="app_show_Apprenants", methods={"GET"})
     */
   /* public function showApprenant(UsersRepository $etudiantrepository)
    {
 
        return $this->render('intervenants/apprenantIntervenant.html.twig',[

         'etudiants' => $etudiantrepository ->findByEtudiant(),

        ]);

    }*/
}
