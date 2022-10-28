<?php

namespace App\Controller;

use App\Entity\Etudiants;
use App\Entity\Tuteurs;
use App\Form\EtudiantsType;
use App\Form\FiltreType;
use App\Form\filtres\FiltreApprenantType;
use App\Repository\EtudiantsRepository;
use App\Repository\NotesRepository;
use App\Repository\TableauNotesRepository;
use App\Repository\UsersRepository;
use App\Repository\ClassesRepository;
use App\Repository\BlocsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Intervenants;
use App\Entity\Users;
use App\Form\UsersType;
use App\Form\IntervenantsType;
use App\Repository\IntervenantsRepository;
use App\Repository\ModulesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Knp\Component\Pager\PaginatorInterface;
/**
 * @Route("/etudiants")
 */
class EtudiantsController extends AbstractController
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }
    /**
     * @Route("/", name="app_etudiants_index", methods={"GET", "POST"})
     */
    public function index(Request $request,EtudiantsRepository $etudiantsRepository, PaginatorInterface $paginator): Response
    {
        $form = $this->createForm(FiltreType::class);
        $form->handleRequest($request);
        $form2 = $this->createForm(FiltreApprenantType::class);
        $form2->handleRequest($request);

    

        if ($form2->isSubmitted() && $form2->isValid()) {
            $value = $form2->get('search')->getData();
        $module = $form2->get('module')->getData();
        $classe = $form2->get('classe')->getData();
        
            if($module == null){
                $module = empty($module);
            }
            if($value == null){
                $value = empty($value);
            }
            if($classe == null){
                $classe = empty($classe);
            }
       
        $etudiants =  $etudiantsRepository->searchMot($value,$module,$classe);



        $etudiants = $paginator->paginate(
            $etudiants, // Requête contenant les données à paginer (ici nos articles)
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            10 // Nombre de résultats par page
        );
            return $this->renderForm('etudiants/index.html.twig', [
                'etudiants' => $etudiants,
                'form2' => $form2,
            ]);
        }

        $etudiants =  $etudiantsRepository->findAll();



        $etudiants = $paginator->paginate(
            $etudiants, // Requête contenant les données à paginer (ici nos articles)
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            10 // Nombre de résultats par page
        );
            return $this->renderForm('etudiants/index.html.twig', [
            'etudiants' => $etudiants,
            'form2' => $form2,
         
        ]);
    }

    /**
     * @Route("/new", name="app_etudiants_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EtudiantsRepository $etudiantsRepository,  UsersRepository $usersRepository, EntityManagerInterface $entityManager, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $etudiant = new Etudiants();
        $user = new Users();
        $form = $this->createForm(EtudiantsType::class, $etudiant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $date = new \DateTimeImmutable('now');
         
            $etudiant->setCreatedBy($this->getUser()->getEmail());
            $etudiant->setUser($user);
            $etudiant->setEmail($form->get('user')->get('email')->getData());
            $etudiant->setCreatedAt($date);
            $etudiant->setClasses($form->get('classes')->getData());
            $etudiantsRepository->add($etudiant);

            $password = $passwordEncoder->encodePassword($user, $form->get('user')->get('password')->getData());
            $user->setPassword($password);
          
            $date = new \DateTimeImmutable('now');
         
            $user->setCreatedBy($this->getUser()->getEmail());
            $user->setUser($user);
            $user->setEmail($form->get('user')->get('email')->getData());
            $user->setNom($form->get('nom')->getData());
            $user->setPrenom($form->get('prenom')->getData());
            $user->setAdresse($form->get('adresse')->getData());
            
            $user->setRoles(['ROLE_ETUDIANT']);
            $user->setCreatedAt($date);
            $user->setPassword($password);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
     

            return $this->redirectToRoute('app_etudiants_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('etudiants/new.html.twig', [
            'etudiant' => $etudiant,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_etudiants_show", methods={"GET"})
     */
    public function show(Etudiants $etudiant, NotesRepository $notesRepository,ClassesRepository $classesRepository,BlocsRepository $blocsRepository,TableauNotesRepository $TableauNotesRepository): Response
    {

        $tableaunotes = $TableauNotesRepository->paretudiant($etudiant);

     $notes = $notesRepository->findByetudiant($etudiant);


     $classes = $classesRepository->findOneBy(['id'=>$etudiant->getClasses()]);

     $blocs = $blocsRepository->findBy(['Classe'=>$classes]);


        return $this->render('etudiants/show.html.twig', [
            'etudiant' => $etudiant,
            'tableaunotes' => $tableaunotes,
            'blocs' => $blocs,
            'notes' => $notes,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_etudiants_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Etudiants $etudiant, EtudiantsRepository $etudiantsRepository): Response
    {
        $form = $this->createForm(EtudiantsType::class, $etudiant)->remove('password');
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $etudiantsRepository->add($etudiant);
            return $this->redirectToRoute('app_etudiants_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('etudiants/edit.html.twig', [
            'etudiant' => $etudiant,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_etudiants_delete", methods={"POST"})
     */
    public function delete(Request $request, Etudiants $etudiant, EtudiantsRepository $etudiantsRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$etudiant->getId(), $request->request->get('_token'))) {
            $etudiantsRepository->remove($etudiant);
        }

        return $this->redirectToRoute('app_etudiants_index', [], Response::HTTP_SEE_OTHER);
    }
}
