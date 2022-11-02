<?php

namespace App\Controller;

use App\Form\FiltreType;
use App\Repository\IntervenantsRepository;
use App\Form\filtres\FiltreIntervenantType;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AnnuaireController extends AbstractController
{
    #[Route('/annuaire', name: 'app_annuaire')]

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
        
            if($module == null){
                $module = empty($module);
            }
            if($value == null){
                $value = empty($value);
            }
            if($classe == null){
                $classe = empty($classe);
            }
       
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
        return $this->renderForm('annuaire/index.html.twig', [
            'intervenants' => $intervenants,
            'form' => $form,
            'form2' => $form2,
            
        ]);
    }


}
