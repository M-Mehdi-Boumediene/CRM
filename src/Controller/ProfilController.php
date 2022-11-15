<?php

namespace App\Controller;

use App\Entity\Users;
use App\Entity\Profil;
use App\Entity\Cv;
use App\Form\CvType;
use App\Form\ProfilType;
use App\Repository\UsersRepository;
use App\Repository\CvRepository;
use App\Repository\ProfilRepository;
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
    public function index(ProfilRepository $profilRepository,Request $request ,UsersRepository $usersRepository,EntityManagerInterface $entityManager, CvRepository $cvRepository): Response
    {

        $profil = new Profil();
        $idUser = $this->getUser();
     
       // $users = $usersRepository -> findById($idUser);
       
            
         $user = $usersRepository->findOneBy(['id'=>$idUser]);
         $nom = $user->getNom();
         $prenom = $user->getPrenom();
         $adresse = $user->getAdresse();
         $telephone = $user->getTelephone();




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

            $user->setNom($form->get('nom')->getData());
            $user->setPrenom($form->get('prenom')->getData());

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
     * @Route("/profil/{id}", name="app_profil_show", methods={"GET"})
     */
    public function show(Profil $profil, CvRepository $cvRepository): Response
    {

        $lecv = $cvRepository->findBy(array('user'=>$this->getUser()));

        return $this->render('profil/show.html.twig', [
      
            'profil' => $profil,
            'lecv'=>$lecv,
        ]);
    }
}
