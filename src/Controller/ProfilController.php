<?php

namespace App\Controller;

use App\Entity\Users;
use App\Entity\Profil;
use App\Entity\Justifications;
use App\Entity\Cv;
use App\Form\CvType;
use App\Form\ProfilType;
use App\Form\JustificationsType;
use App\Repository\UsersRepository;
use App\Repository\CvRepository;
use App\Repository\ProfilRepository;
use App\Repository\JustificationsRepository;
use App\Repository\EtudiantsRepository;
use App\Repository\TableauNotesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProfilController extends AbstractController
{
   /**
     * @Route("/profil", name="app_profil", methods={"GET", "POST"})
     */
    public function index(ProfilRepository $profilRepository,Request $request ,UsersRepository $usersRepository,EtudiantsRepository $etudiantsRepository,EntityManagerInterface $entityManager, CvRepository $cvRepository, JustificationsRepository $justificationsRepository): Response
    {

        $profil = new Profil();
        $idUser = $this->getUser();
     
       // $users = $usersRepository -> findById($idUser);
       
            
         $userr = $usersRepository->findOneBy(array('id'=>$idUser));
         
         $nom = $userr->getNom();
         $prenom = $userr->getPrenom();
         $adresse = $userr->getAdresse();
         $telephone = $userr->getTelephone();




         $form = $this->createForm(ProfilType::class, $profil);
         $form->handleRequest($request);

    


 
         if ($form->isSubmitted() && $form->isValid()) {
           
           $profil->setUser($this->getUser());
            $profil->setNom($form->get('nom')->getData());
            $profil->setPrenom($form->get('prenom')->getData());
            $profil ->setImage($form->get('image')->getData());
            $profil ->setAdresse($form->get('adresse')->getData());
            $profil ->setTelephone($form->get('telephone')->getData());
            //  $profil ->setUser($idUser);

          

            $images=$profil ->setImage($form->get('image')->getData());

              $img=  $form->get('image')->getData();
              $photodeprofil = $profilRepository->findOneBy(['user'=>$this->getUser()]);

              
              
                // On génère un nouveau nom de fichier
                $fichier = md5(uniqid()).'.'.$img->guessExtension();
                
                // On copie le fichier dans le dossier uploads
                $img->move(
                    $this->getParameter('videos_directory'),
                    $fichier
                );
            
          
            $profil ->setImage($fichier);
      


            $entityManager->persist($profil);
            $entityManager->flush();



            $em = $this->getDoctrine()->getManager();
            $qb = $em->createQueryBuilder();
            $q = $qb->update(Users::class, 'u')

                ->set('u.profil','?1')
                ->where('u.id = ?2')
                ->setParameter(1,$profil)
                ->setParameter(2,$this->getUser())
        
                ->getQuery();
            $p = $q->execute();



          
            return $this->redirectToRoute('app_profil', ['profil' => $profil,], Response::HTTP_SEE_OTHER);  
        


        }
        $form2 = $this->createForm(CvType::class);
        $form2->handleRequest($request);
        
        if ($form2->isSubmitted() && $form2->isValid()) {
            $cv = new Cv();
          
          
            $cv->setUser($this->getUser());
            $cv->setType($form2->get('type')->getData());
            $cv->setEntreprise($form2->get('entreprise')->getData());
            $cv->setEntreprise($form2->get('entreprise')->getData());
            $cv->setEcole($form2->get('ecole')->getData());
            $cv->setTitre($form2->get('titre')->getData());
            $cv->setFormation($form2->get('formation')->getData());
            $cv->setDebut($form2->get('debut')->getData());
            $cv->setFin($form2->get('fin')->getData());
            $cv->setDescription($form2->get('description')->getData());

            $cvRepository->add($cv, true);

             return $this->redirectToRoute('app_profil', ['profil' => $profil,], Response::HTTP_SEE_OTHER);  
         
 
 
         }
 
        $photoprofil = $profilRepository->findOneBy(array('user'=>$this->getUser()));

        $etudiant = $etudiantsRepository->findOneBy(array('user'=>$this->getUser()));
       
        $lecv = $cvRepository->findBy(array('user'=>$this->getUser()));




     

        return $this->renderForm('profil/index.html.twig', [
            'nom' => $nom,
            'prenom' => $prenom,
            'form' => $form,
            'form2' => $form2,
        
            'profil' => $profil,
            'photoprofil'=>$photoprofil,
            'lecv'=>$lecv,
     
        ]);
    }
     /**
     * @Route("/profil/{id}", name="app_profil_show", methods={"GET","POST"})
     */
    public function show(Request $request,Profil $profil, CvRepository $cvRepository, TableauNotesRepository $TableauNotesRepository, ProfilRepository $profilRepository, EtudiantsRepository $etudiantsRepository, JustificationsRepository $justificationsRepository): Response
    {

        $lecv = $cvRepository->findBy(array('user'=>$this->getUser()));

        
        $tableaunotes = $TableauNotesRepository->paretudiant1($profil);
        $tableaunotes2 = $TableauNotesRepository->paretudiant2($profil);
        
        $tableaunotes3 = $TableauNotesRepository->paretudiant3($profil);
        $tableaunotes4 = $TableauNotesRepository->paretudiant4($profil);

        $tableaunotesexam = $TableauNotesRepository->paretudiant1exam($profil);
        $tableaunotes2exam = $TableauNotesRepository->paretudiant2exam($profil);
        $tableaunotes3exam = $TableauNotesRepository->paretudiant3exam($profil);
        $tableaunotes4exam = $TableauNotesRepository->paretudiant4exam($profil);
        $photoprofil = $profilRepository->findOneBy(array('user'=>$this->getUser()));
        $etudiant = $etudiantsRepository->findOneBy(array('user'=>$this->getUser()));

        $form3 = $this->createForm(JustificationsType::class);

        $form3->handleRequest($request);

        if ($form3->isSubmitted() && $form3->isValid()) {
            $files = $form3->get('files')->getData();
            $message = $form3->get('message')->getData();
            $tableau = $form3->get('tableau')->getData();
            foreach($files as $file){
                // Je génère un nouveau nom de fichier
                $fichier = md5(uniqid()) . '.' . $file->guessExtension();

                // Je copie le fichier dans le dossier uploads
                $file->move(
                    $this->getParameter('videos_directory'),
                    $fichier
                );

                // Je stocke le document dans la BDD (nom du fichier)
                $justification = new Justifications();
                $justification->setPath($fichier);
                $justification->setMessage($message);
                $justification->setUser($this->getUser());
                $justification->setTableauabsence($tableau);

             
            }
            
            $justificationsRepository->add($justification, true);

            return $this->redirectToRoute('app_justifications_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('profil/show.html.twig', [
            'photoprofil'=>$photoprofil,
            'etudiant'=>$etudiant,
            'tableaunotes' => $tableaunotes,
            'tableaunotesexam' => $tableaunotesexam,
            'tableaunotes2' => $tableaunotes2,
            'tableaunotes2exam' => $tableaunotes2exam,
            'tableaunotes3' => $tableaunotes3,
            'tableaunotes3exam' => $tableaunotes3exam,
            'tableaunotes4' => $tableaunotes4,
            'tableaunotes4exam' => $tableaunotes4exam,
            'form3' => $form3,
            'profil' => $profil,
            'lecv'=>$lecv,
        ]);
    }
}
