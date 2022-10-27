<?php

namespace App\Controller;

use App\Entity\Entreprises;
use App\Form\EntreprisesType;
use App\Form\FiltreType;
use App\Form\FiltreEntrepriseType;
use App\Repository\EntreprisesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Users;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/entreprises")
 */
class EntreprisesController extends AbstractController
{
    /**
     * @Route("/", name="app_entreprises_index", methods={"GET", "POST"})
     */
    public function index(Request $request, EntreprisesRepository $entreprisesRepository, PaginatorInterface $paginator): Response
    {
        $form = $this->createForm(FiltreType::class);
        $form->handleRequest($request);

        $form2 = $this->createForm(FiltreEntrepriseType::class);
        $form2->handleRequest($request);
        
        $value = $form2->get('search')->getData();
        $entreprises =  $entreprisesRepository->searchMot($value);

        if ($form2->isSubmitted() && $form2->isValid()) {

              $entreprises = $paginator->paginate(
            $entreprises, // Requête contenant les données à paginer (ici nos articles)
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            10 // Nombre de résultats par page
        );
            return $this->renderForm('entreprises/index.html.twig', [
                'entreprises' => $entreprises,
                'form' => $form,
                'form2' => $form2,
            ]);

        }

        $entreprises =  $entreprisesRepository->findAll();
        $entreprises = $paginator->paginate(
            $entreprises, // Requête contenant les données à paginer (ici nos articles)
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            10 // Nombre de résultats par page
        );
        return $this->renderForm('entreprises/index.html.twig', [
            'entreprises' => $entreprisesRepository->findAll(),
            'form2' => $form2,
        ]);
    }

    /**
     * @Route("/new", name="app_entreprises_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntreprisesRepository $entreprisesRepository, EntityManagerInterface $entityManager, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $entreprise = new Entreprises();
        $user = new Users();
        $form = $this->createForm(EntreprisesType::class, $entreprise);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $date = new \DateTimeImmutable('now');
         
            $entreprise->setCreatedBy($this->getUser()->getEmail());
            $entreprise->setUsers($user);
            $entreprise->setCreatedAt($date);
            $entreprisesRepository->add($entreprise);
            
            $password = $passwordEncoder->encodePassword($user, $form->get('users')->get('password')->getData());
            $user->setPassword($password);


            $user->setCreatedBy($this->getUser()->getEmail());
            $user->setUser($user);
            $user->setEmail($form->get('users')->get('email')->getData());
            $user->setNom($form->get('nom')->getData());
 
            $user->setAdresse($form->get('adresse')->getData());
            
            $user->setRoles(['ROLE_ENTREPRISE']);
            $user->setCreatedAt($date);
            $user->setPassword($password);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
     

            return $this->redirectToRoute('app_entreprises_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('entreprises/new.html.twig', [
            'entreprise' => $entreprise,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_entreprises_show", methods={"GET"})
     */
    public function show(Entreprises $entreprise): Response
    {
        return $this->render('entreprises/show.html.twig', [
            'entreprise' => $entreprise,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_entreprises_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Entreprises $entreprise, EntreprisesRepository $entreprisesRepository): Response
    {
        $form = $this->createForm(EntreprisesType::class, $entreprise);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entreprisesRepository->add($entreprise);
            return $this->redirectToRoute('app_entreprises_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('entreprises/edit.html.twig', [
            'entreprise' => $entreprise,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_entreprises_delete", methods={"POST"})
     */
    public function delete(Request $request, Entreprises $entreprise, EntreprisesRepository $entreprisesRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$entreprise->getId(), $request->request->get('_token'))) {
            $entreprisesRepository->remove($entreprise);
        }

        return $this->redirectToRoute('app_entreprises_index', [], Response::HTTP_SEE_OTHER);
    }
}
