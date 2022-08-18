<?php

namespace App\Controller;

use App\Entity\Documents;
use App\Entity\Modules;
use App\Form\ModulesType;
use App\Repository\UsersRepository;
use App\Repository\ModulesRepository;
use App\Repository\IntervenantsRepository;
use App\Entity\Files;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/modules")
 */
class ModulesController extends AbstractController
{
    /**
     * @Route("/", name="app_modules_index", methods={"GET"})
     */
    public function index(ModulesRepository $modulesRepository): Response
    {
        return $this->render('modules/index.html.twig', [
            'modules' => $modulesRepository->findAll(),
        ]);
    }
    /**
     * @Route("/new", name="app_modules_new", methods={"GET", "POST"})
     */
    public function new(Request $request, ModulesRepository $modulesRepository): Response
    {
        $module = new Modules();
        $form = $this->createForm(ModulesType::class, $module);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $date = new \DateTimeImmutable('now');
            $files = $form->get('files')->getData();
            $videos = $form->get('documents')->getData();
           
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
                $module->addFile($file);

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
                $module->addDocument($media);

            }

            $module->setCreatedBy($this->getUser()->getEmail());
            $module->setUsers($this->getUser());
            $module->setCreatedAt($date);
            $modulesRepository->add($module);



            return $this->redirectToRoute('app_modules_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('modules/new.html.twig', [
            'module' => $module,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_modules_show", methods={"GET"})
     */
    public function show(Modules $module,UsersRepository $intervenantsRepository,UsersRepository $etudiantsRepository): Response
    {
        $id = $module->getClasses();
        return $this->render('modules/show.html.twig', [
            'module' => $module,
            'intervenants' => $intervenantsRepository->findByClasse($id),
            'etudiants' => $etudiantsRepository->findByEtudiant(),
        
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_modules_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Modules $module, ModulesRepository $modulesRepository): Response
    {
        $form = $this->createForm(ModulesType::class, $module);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $modulesRepository->add($module);
            return $this->redirectToRoute('app_modules_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('modules/edit.html.twig', [
            'module' => $module,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_modules_delete", methods={"POST"})
     */
    public function delete(Request $request, Modules $module, ModulesRepository $modulesRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$module->getId(), $request->request->get('_token'))) {
            $modulesRepository->remove($module);
        }

        return $this->redirectToRoute('app_modules_index', [], Response::HTTP_SEE_OTHER);
    }
}