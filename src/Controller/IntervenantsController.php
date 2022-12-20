<?php

namespace App\Controller;

use App\Entity\Intervenants;
use App\Entity\Users;
use App\Form\UsersType;
use App\Form\IntervenantsType;
use App\Form\FiltreType;
use App\Form\filtres\FiltreIntervenantType;
use App\Repository\IntervenantsRepository;
use App\Repository\ModulesRepository;
use App\Repository\UsersRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Knp\Component\Pager\PaginatorInterface;

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
     * @Route("/", name="app_intervenants_index", methods={"GET", "POST"})
     */
    public function index(Request $request,IntervenantsRepository $intervenantRepository, PaginatorInterface $paginator): Response
    {
        $form = $this->createForm(FiltreType::class);
        $form->handleRequest($request);
        $form2 = $this->createForm(FiltreIntervenantType::class);
        $form2->handleRequest($request);

    

        if ($form2->isSubmitted() && $form2->isValid()) {
            $value = $form2->get('search')->getData();
        $module = $form2->get('module')->getData();
        $classe = $form2->get('classe')->getData();
        
       
        $intervenants =  $intervenantRepository->searchMot($value,$module,$classe);
        $intervenants = $paginator->paginate(
            $intervenants, // Requête contenant les données à paginer (ici nos articles)
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            10 // Nombre de résultats par page
        );
            return $this->renderForm('intervenants/index.html.twig', [
                'intervenants' => $intervenants,
                'form2' => $form2,
            ]);
        }
        $intervenants =  $intervenantRepository->findAll();
        $intervenants = $paginator->paginate(
            $intervenants, // Requête contenant les données à paginer (ici nos articles)
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            10 // Nombre de résultats par page
        );
        return $this->renderForm('intervenants/index.html.twig', [
            'intervenants' => $intervenants,
            'form' => $form,
            'form2' => $form2,
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
            $intervenant->setUser($user);
            $intervenant->setCreatedAt($date);
            $intervenant->setEmail($form->get('user')->get('email')->getData());
            /*
            $intervenant->setVille($form->get('ville')->getData());
            */


            $modules = $form->get('modules')->getData();
            foreach($modules as $module){
                $intervenant->addModule($module);
                $module->addIntervenant($intervenant);
            
            }
            $lesclasses = $form->get('classes')->getData();

            foreach($lesclasses as $lesclasse){
                $intervenant->addClass($lesclasse);
                $lesclasse->addIntervenant($intervenant);
            
            }
            $apprenants = $form->get('apprenants')->getData();
            foreach($apprenants as $apprenant){
                $intervenant->addApprenant($apprenant);
                $apprenant->setIntervenants($intervenant);
            
            }
            $intervenantsRepository->add($intervenant);

      
            $password = $passwordEncoder->encodePassword($user, $form->get('user')->get('password')->getData());
            $user->setPassword($password);

 




            $date = new \DateTimeImmutable('now');
         
            $user->setCreatedBy($this->getUser()->getEmail());
            $user->setUser($user);
            $user->setNom($form->get('nom')->getData());
            $user->setPrenom($form->get('prenom')->getData());
            $user->setAdresse($form->get('adresse')->getData());
        
            
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

   
    
        return $this->render('intervenants/show.html.twig', [
    
            'intervenant' => $intervenant,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_intervenants_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request,Users $user, Intervenants $intervenant, IntervenantsRepository $intervenantsRepository, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $form = $this->createForm(IntervenantsType::class, $intervenant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $password = $passwordEncoder->encodePassword($user, $form->get('user')->get('password')->getData());
            $user->setPassword($password);
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
