<?php

namespace App\Controller;

use App\Entity\Cours;
use App\Entity\Modules;
use App\Form\CoursType;
use App\Form\ModulesType;
use App\Form\filtres\FiltreCoursType;
use App\Repository\CoursRepository;
use App\Repository\ModulesRepository;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;

#[Route('/cours')]
class CoursController extends AbstractController
{
    #[Route('/', name: 'app_cours_index', methods: ['GET','POST'])]
    public function index(Request $request,CoursRepository $coursRepository, PaginatorInterface $paginator): Response
    {

        
        $form2 = $this->createForm(FiltreCoursType::class);
        $form2->handleRequest($request);

        $cours =  $coursRepository->findAll();

        if ($form2->isSubmitted() && $form2->isValid()) {
            $value = $form2->get('search')->getData();
        $filtre = $form2->get('module')->getData();
   
        
            if($filtre == null){
                $filtre = empty($filtre);
            }
            if($value == null){
                $value = empty($value);
            }
    
       
        $cours =  $coursRepository->searchMot($value,$filtre);

        $cours = $paginator->paginate(
            $cours, // Requête contenant les données à paginer (ici nos articles)
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            10 // Nombre de résultats par page
        );
            return $this->renderForm('cours/index.html.twig', [
                'cours' => $cours,
                'form2' => $form2,
            ]);
        }
 
        $cours =  $coursRepository->findAll();

        $cours = $paginator->paginate(
            $cours, // Requête contenant les données à paginer (ici nos articles)
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            10 // Nombre de résultats par page
        );

        return $this->renderForm('cours/index.html.twig', [
            'cours' => $cours,
            'form2' => $form2,
        ]);
    }

    #[Route('/new', name: 'app_cours_new', methods: ['GET', 'POST'])]
    public function new(Request $request, CoursRepository $coursRepository, ModulesRepository $modulesRepository): Response
    {
        $cour = new Cours();
        $form = $this->createForm(CoursType::class, $cour);
        $form->handleRequest($request);
        $files = $form->get('files')->getData();
        $videos = $form->get('documents')->getData();
        if ($form->isSubmitted() && $form->isValid()) {

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
                $cour->addFiles($file);

            }
            foreach($videos as $video){
                // Je génère un nouveau nom de fichier
                $fichier = md5(uniqid()) . '.' . $video->guessExtension();

                // Je copie le fichier dans le dossier uploads
                $video->move(
                    $this->getParameter('videos_directory'),
                    $fichier
                );

                // Je stocke la video dans la BDD (nom du fichier)
                $media= new Documents();
                $media->setName($fichier);
                $media->setNom($fichier);
                $cour->addDocuments($media);

            }

            $date = new \DateTimeImmutable('now');
            $cour->setCreatedAt($date);
            $cour->setCreatedBy($this->getUser()->getEmail());
            $coursRepository->add($cour, true);

            return $this->redirectToRoute('app_cours_index', [], Response::HTTP_SEE_OTHER);
        }

        $module = new Modules();
        $form2 = $this->createForm(ModulesType::class, $module);
        $form2->handleRequest($request);

        if ($form2->isSubmitted() && $form2->isValid()) {
            $date = new \DateTimeImmutable('now');
         
            $module->setCreatedBy($this->getUser()->getEmail());
        
            $module->setCreatedAt($date);
            $modulesRepository->add($module);
            return $this->redirectToRoute('app_modules_new', [], Response::HTTP_SEE_OTHER);
        }
        
        return $this->renderForm('cours/new.html.twig', [
            'cour' => $cour,
            'form' => $form,
            'form2' => $form2,
        ]);
    }

    #[Route('/{id}', name: 'app_cours_show', methods: ['GET'])]
    public function show(Cours $cour): Response
    {
        return $this->render('cours/show.html.twig', [
            'cour' => $cour,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_cours_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Cours $cour, CoursRepository $coursRepository): Response
    {
        $form = $this->createForm(CoursType::class, $cour);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $coursRepository->add($cour, true);

            return $this->redirectToRoute('app_cours_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('cours/edit.html.twig', [
            'cour' => $cour,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_cours_delete', methods: ['POST'])]
    public function delete(Request $request, Cours $cour, CoursRepository $coursRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$cour->getId(), $request->request->get('_token'))) {
            $coursRepository->remove($cour, true);
        }

        return $this->redirectToRoute('app_cours_index', [], Response::HTTP_SEE_OTHER);
    }
}
