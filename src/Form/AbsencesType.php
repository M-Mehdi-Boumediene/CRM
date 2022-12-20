<?php

namespace App\Form;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Doctrine\ORM\EntityRepository;
use App\Entity\Classes;
use App\Entity\Calendrier;
use App\Entity\Blocs;
use App\Entity\Modules;
use App\Entity\Users;
use App\Entity\Absences;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
class AbsencesType extends AbstractType
{
    private $em;

    private $tokenStorage;
    public function __construct(TokenStorageInterface   $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        if(in_array('ROLE_INTERVENANT', $this->tokenStorage->getToken()->getUser()->getRoles())){
            $builder

            ->add('classe', EntityType::class, [
                'class' => Classes::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')

                    ->innerJoin('u.modules', 'm')

                    ->andWhere('m.classes = :user')

                    ->setParameter('user',$this->tokenStorage->getToken()->getUser()->getClasse())
                        ->orderBy('u.nom', 'ASC');
                },
                'expanded' => false,
                'multiple' => false,
                'choice_label' => 'nom',
                'empty_data'=>'',
                'required' => true,
                'label'=>false,
                'placeholder'=>'',
         
            ])
         
            ->add('tableau', CollectionType::class, [
                'entry_type' => TableauAbsencesType::class,
                'entry_options' => ['label' => false],
                'allow_add' => true,
                'prototype' => true,
      
           
                'by_reference' => false,
                'label' => false
                
            ])
              
            ->add('calendrier', EntityType::class, [
                'class' => Calendrier::class,
                'expanded' => false,
                'multiple' => false,
                'choice_label' => 'nom',
                'required'=>false,
                'label'=>'Classe',
                'choice_label' => function ($category) {
                    return $category->getTitre() . ' ' . 'du '.$category->getStart()->format('d/m/Y H:m') .' au '. $category->getEnd()->format('d/m/Y  H:m').'';
                },
            ])
                
            ;
        }else{
            $builder

            ->add('classe', EntityType::class, [
                'class' => Classes::class,
                'expanded' => false,
                'multiple' => false,
                'choice_label' => 'nom',
                'required'=>false,
                'label'=>'Classe',
            ])
            ->add('calendrier', EntityType::class, [
                'class' => Calendrier::class,
                'expanded' => false,
                'multiple' => false,
                'choice_label' => 'nom',
                'required'=>false,
                'label'=>'Calendrier',
                'choice_label' => function ($category) {
                    return $category->getTitre() . ' ' . 'du '.$category->getStart()->format('d/m/Y H:m') .' au '. $category->getEnd()->format('d/m/Y  H:m').'';
                },
            ])
            ->add('tableau', CollectionType::class, [
                'entry_type' => TableauAbsencesType::class,
                'entry_options' => ['label' => false],
                'allow_add' => true,
                'prototype' => true,
      
           
                'by_reference' => false,
                'label' => false
                
            ])
               
                
            ;
        }
   
    }

   
}
