<?php

namespace App\Form;

use App\Entity\Etudiants;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;
use App\Entity\Modules;
use App\Entity\Classes;
use App\Entity\Blocs;
use App\Entity\Tuteurs;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use App\Entity\Intervenants;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use App\Entity\Codepostal;
use App\Entity\Villes;
use App\Form\UsersType;
class FiltreType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('classe', EntityType::class, [
            'class' => Classes::class,
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('u')
                    ->orderBy('u.nom', 'ASC');
            },
            'choice_label' => 'nom',
            'label'=>false,
            'placeholder'=>'Classe',
            'multiple' => false,
            'required' => false
        ])
        ->add('bloc', EntityType::class, [
            'class' => Blocs::class,
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('u')
                    ->orderBy('u.nom', 'ASC');
            },
            'choice_label' => 'nom',
            'label'=>false,
            'placeholder'=>'Bloc',
            'multiple' => false,
            'required' => false
        ])

        ->add('module', EntityType::class, [
            'class' => Modules::class,
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('u')
                    ->orderBy('u.nom', 'ASC');
            },
            'choice_label' => 'nom',
            'label'=>false,
            'placeholder'=>'Module',
            'multiple' => false,
            'required' => false
        ])
        ->add('apprenant', EntityType::class, [
            'class' => Etudiants::class,
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('u')
                    ->orderBy('u.email', 'ASC');
            },
            'choice_label' => function ($category) {
                return $category->getNom() . ' ' . $category->getPrenom();
            },
            'label'=>false,
            'placeholder'=>'Apprenant',
            'multiple' => false,
            'required' => false
        ])
 
        ->add('intervenants', EntityType::class, [
            'class' => Intervenants::class,
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('u')
                    ->orderBy('u.email', 'ASC');
            },
            'choice_label' => function ($category) {
                return $category->getNom() . ' ' . $category->getPrenom();
            },
            'placeholder'=>'Intervenant',
            'label'=>false,
            'multiple' => false,
            'required' => false
        ])
 

   
    ;


 
    


   
    }


}
