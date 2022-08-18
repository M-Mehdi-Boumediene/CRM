<?php

namespace App\Form;

use App\Entity\Classes;
use App\Entity\Etudiants;
use App\Entity\Intervenants;
use App\Entity\Notes;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NotesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('note',TextType::class,[
                'label'=>'note',
            ])
            ->add('coefmodule',TextType::class,[
                'label'=>'Coefficient module',
            ])
            ->add('coefbloc',TextType::class,[
                'label'=>'Coefficient bloc',
            ])
            ->add('moy',TextType::class,[
                'label'=>'Moyenne',
            ])
            ->add('moygeneral',TextType::class,[
                'label'=>'Moyenne générale',
            ])
            ->add('module', EntityType::class, [
                'class' => Classes::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->orderBy('u.nom', 'ASC');
                },
                'choice_label' => 'nom',
              
            ])
      
            ->add('apprenant', EntityType::class, [
                'class' => Etudiants::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->orderBy('u.nom', 'ASC');
                },
                'choice_label' => function (Etudiants $etudiant) {
                    return $etudiant->getNom() . ' ' . $etudiant->getPrenom();
            
                    // or better, move this logic to Customer, and return:
                    // return $customer->getFullname();
                },
               
                ])
           
            ->add('intervenant', EntityType::class, [
                'class' => Intervenants::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->orderBy('u.nom', 'ASC');
                },
                'choice_label' => function (Intervenants $intervenant) {
                    return $intervenant->getNom() . ' ' . $intervenant->getPrenom();
            
                    // or better, move this logic to Customer, and return:
                    // return $customer->getFullname();
                },
              
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Notes::class,
        ]);
    }
}
