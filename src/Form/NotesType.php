<?php

namespace App\Form;

use App\Entity\Classes;
use App\Entity\Etudiants;
use App\Entity\Intervenants;
use App\Entity\Modules;

use App\Entity\Blocs;
use App\Entity\Notes;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use App\Form\DataTransformer\ClassesToNumbersTransformer;
use Symfony\Component\Form\FormInterface;
class NotesType extends AbstractType
{
    private $em;

    
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        
        $builder
        ->add('type', ChoiceType::class, [
            'choices' => [
      
                'DEVOIR' => 'DEVOIR',
                'EXAMEN' => 'EXAMEN',

                
            ],
 
            'required' => true,
           
            'label' => false 
        ])
        ->add('semestre', ChoiceType::class, [
            'choices' => [
                '1er semestre' => '1',
                '2ème semestre' => '2',
                '3ème semestre' => '3',

            ],
 
            'required' => true,
           
            'label' => false 
        ])
            ->add('moduleid', EntityType::class, [
                'class' => Modules::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->orderBy('u.nom', 'ASC');
                },
                'choice_label' => 'nom',
                'label'=>false,
                'required' => true,
            ])
            ->add('classes', EntityType::class, [
                'class' => Classes::class,
                'expanded' => false,
                'multiple' => false,
                'choice_label' => 'nom',
                'empty_data'=>'',
                'required' => true,
                'label'=>false,
                'placeholder'=>'',
         
            ])
            ->add('blocid', EntityType::class, [
                'class' => Blocs::class,
                'label'=>false,
                'choice_label' => 'nom',
                'empty_data'=>'',
                'required' => true,
              
         
            ])
      
            ->add('tableau', CollectionType::class, [
                'entry_type' => TableauNotesType::class,
                'entry_options' => ['label' => false],
                'allow_add' => true,
                'prototype' => true,
      
                'attr' => array (
                        'class' => "child-collection",
                ),
                'by_reference' => false,
                'label' => false
                
            ])

        ;
    

    


    

   
        
    }


}