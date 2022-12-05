<?php

namespace App\Controller;

use App\Entity\Tuteurs;
use App\Form\TuteursType;
use App\Form\FiltreType;
use App\Form\filtres\FiltreTuteurType;
use App\Repository\UsersRepository;
use App\Repository\TuteursRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Knp\Component\Pager\PaginatorInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Users;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/tuteurs")
 */
class TuteursController extends AbstractController
{
    /**
     * @Route("/", name="app_tuteurs_index", methods={"GET", "POST"})
     */
    public function index(Request $request, TuteursRepository $tuteursRepository, PaginatorInterface $paginator): Response
    {
        $form = $this->createForm(FiltreType::class);
        $form->handleRequest($request);

        $form2 = $this->createForm(FiltreTuteurType::class);
        $form2->handleRequest($request);
        
        $value = $form2->get('search')->getData();
        $tuteurs =  $tuteursRepository->searchMot($value);
        if ($form2->isSubmitted() && $form2->isValid()) {

            $tuteurs = $paginator->paginate(
                $tuteurs, // Requête contenant les données à paginer (ici nos articles)
                $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
                10 // Nombre de résultats par page
            );
            return $this->renderForm('tuteurs/index.html.twig', [
                'tuteurs' => $tuteurs,
                'form' => $form,
                'form2' => $form2,
            ]);

        }
        $tuteurs =  $tuteursRepository->findAll();
        $tuteurs = $paginator->paginate(
            $tuteurs, // Requête contenant les données à paginer (ici nos articles)
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            10 // Nombre de résultats par page
        );
        return $this->renderForm('tuteurs/index.html.twig', [
            'tuteurs' => $tuteursRepository->findAll(),
            'form' => $form,
            'form2' => $form2,
        ]);
    }

    /**
     * @Route("/new", name="app_tuteurs_new", methods={"GET", "POST"})
     */
    public function new(Request $request, TuteursRepository $tuteursRepository, EntityManagerInterface $entityManager, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $tuteur = new Tuteurs();
        $user = new Users();
        $form = $this->createForm(TuteursType::class, $tuteur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $date = new \DateTimeImmutable('now');
            $apprenants = $form->get('etudiants')->getData();
            $tuteur->setCreatedBy($this->getUser()->getEmail());
            $tuteur->setUsers($user);
            $tuteur->setCreatedAt($date);
            $apprenants = $form->get('etudiants')->getData();
            foreach($apprenants as $apprenant){
                $tuteur->addEtudiant($apprenant);
                $apprenant->addTuteur($tuteur);
            
            }
            $tuteursRepository->add($tuteur);
            
            $password = $passwordEncoder->encodePassword($user, $form->get('users')->get('password')->getData());
            $user->setPassword($password);


            $user->setCreatedBy($this->getUser()->getEmail());
            $user->setUser($user);
            $user->setEmail($form->get('users')->get('email')->getData());
            $user->setNom($form->get('nom')->getData());
            $user->setPrenom($form->get('prenom')->getData());
            $user->setAdresse($form->get('adresse')->getData());
            
            $user->setRoles(['ROLE_TUTEUR']);
            $user->setCreatedAt($date);
            $user->setPassword($password);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
     
            return $this->redirectToRoute('app_tuteurs_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('tuteurs/new.html.twig', [
            'tuteur' => $tuteur,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_tuteurs_show", methods={"GET"})
     */
    public function show(Tuteurs $tuteur,UsersRepository $etudiantsRepository): Response
    {
        return $this->render('tuteurs/show.html.twig', [
            'tuteur' => $tuteur,
            'etudiants' => $etudiantsRepository->findByEtudiant(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_tuteurs_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Tuteurs $tuteur, TuteursRepository $tuteursRepository): Response
    {
        $form = $this->createForm(TuteursType::class, $tuteur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $apprenants = $form->get('etudiants')->getData();
  
            foreach($apprenants as $apprenant){
      
            $tuteur->addEtudiant($apprenant);
               
 
            }

            $tuteursRepository->add($tuteur);
            return $this->redirectToRoute('app_tuteurs_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('tuteurs/edit.html.twig', [
            'tuteur' => $tuteur,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_tuteurs_delete", methods={"POST"})
     */
    public function delete(Request $request, Tuteurs $tuteur, TuteursRepository $tuteursRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$tuteur->getId(), $request->request->get('_token'))) {
            $tuteursRepository->remove($tuteur);
        }

        return $this->redirectToRoute('app_tuteurs_index', [], Response::HTTP_SEE_OTHER);
    }
}
