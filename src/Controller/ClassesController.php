<?php

namespace App\Controller;

use App\Entity\Classes;
use App\Entity\Modules;
use App\Form\ClassesType;
use App\Form\FiltreType;
use App\Form\SearchType;
use App\Repository\UsersRepository;
use App\Repository\ClassesRepository;
use App\Repository\ModulesRepository;
use App\Repository\EtudiantsRepository;
use App\Repository\IntervenantsRepository;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/classes")
 */
class ClassesController extends AbstractController
{
    /**
     * @Route("/", name="app_classes_index", methods={"GET", "POST"})
     */
    public function index(Request $request, ClassesRepository $classesRepository): Response
    {
        $form = $this->createForm(FiltreType::class);
        $form->handleRequest($request);

        $form2 = $this->createForm(SearchType::class);
        $form2->handleRequest($request);
        
        $value = $form2->get('search')->getData();
        $classes =  $classesRepository->searchMot($value);
        if ($form2->isSubmitted() && $form2->isValid()) {

      
            return $this->renderForm('classes/index.html.twig', [
                'classes' => $classes,
                'form' => $form,
                'form2' => $form2,
            ]);

        }
 

        return $this->renderForm('classes/index.html.twig', [
            'classes' => $classes,
            'form' => $form,
            'form2' => $form2,
        ]);
    }

    /**
     * @Route("/new", name="app_classes_new", methods={"GET", "POST"})
     */
    public function new(Request $request, ClassesRepository $classesRepository): Response
    {
        $class = new Classes();
        $form = $this->createForm(ClassesType::class, $class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $date = new \DateTimeImmutable('now');
         
            $class->setCreatedBy($this->getUser()->getEmail());

            
            $class->setCreatedAt($date);
            $classesRepository->add($class);
            return $this->redirectToRoute('app_classes_index', [], Response::HTTP_SEE_OTHER);
        }

      

        return $this->renderForm('classes/new.html.twig', [
            'class' => $class,
           
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_classes_show", methods={"GET"})
     */
    public function show(Classes $class,ModulesRepository $modulesRepository,UsersRepository $intervenantsRepository,UsersRepository $etudiantsRepository): Response
    {
  
            $id = $class->getId();
        return $this->render('classes/show.html.twig', [
            'class' => $class,
            'classes' => $class,
            'modules' => $modulesRepository->findBy(array('classes'=>$class)),
            'intervenants' => $intervenantsRepository->findByClasse($class),
            'etudiants' => $etudiantsRepository->findByEtudiant($class),
        
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_classes_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Classes $class, ClassesRepository $classesRepository): Response
    {
        $form = $this->createForm(ClassesType::class, $class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $classesRepository->add($class);
            return $this->redirectToRoute('app_classes_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('classes/edit.html.twig', [
            'class' => $class,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_classes_delete", methods={"POST"})
     */
    public function delete(Request $request, Classes $class, ClassesRepository $classesRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$class->getId(), $request->request->get('_token'))) {
            $classesRepository->remove($class);
        }

        return $this->redirectToRoute('app_classes_index', [], Response::HTTP_SEE_OTHER);
    }
}
