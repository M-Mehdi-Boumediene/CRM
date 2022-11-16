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
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

use Symfony\Component\Form\FormInterface;
class NotesType extends AbstractType
{
    private $em;

    private $tokenStorage;
    public function __construct(TokenStorageInterface   $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        
        $builder
        ->add('coefficient')
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
            ->add('module', EntityType::class, [
                'class' => Modules::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')

                    ->andWhere('u.classes = :user')

                    ->setParameter('user',$this->tokenStorage->getToken()->getUser()->getClasse())
                        ->orderBy('u.nom', 'ASC');
                },
                'choice_label' => 'nom',
                'label'=>false,
                'required' => true,
            ])
            ->add('classes', EntityType::class, [
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
            ->add('blocid', EntityType::class, [
                'class' => Blocs::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')

                    ->andWhere('u.classe = :user')

                    ->setParameter('user',$this->tokenStorage->getToken()->getUser()->getClasse())
                        ->orderBy('u.nom', 'ASC');
                },
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
    

    
        $formModifier = function (FormInterface $form, Classes $sport = null) {
            $positions = null === $sport ? [] : $sport->getModules();
            $positions2 = null === $sport ? [] : $sport->getBlocs();
            $form->add('moduleid', EntityType::class, [
                'class' => Modules::class,
                'placeholder' => '',
                'label' => false,
                'choices' => $positions,
            ]);

            $form->add('blocid', EntityType::class, [
                'class' => Blocs::class,
                'placeholder' => '',
                'choices' => $positions2,
                'label' => false,
                'choice_label' => function ($category) {
                    return $category->getNom();
                },
            ]);
        };


        $builder->get('classes')->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) use ($formModifier) {
                // It's important here to fetch $event->getForm()->getData(), as
                // $event->getData() will get you the client data (that is, the ID)
                $sport = $event->getForm()->getData();

                // since we've added the listener to the child, we'll have to pass on
                // the parent to the callback functions!
                $formModifier($event->getForm()->getParent(), $sport);
            }
        );



        $formModifier2 = function (FormInterface $form2, Classes $sport2 = null) {
            $positions2 = null === $sport2 ? [] : $sport2->getBlocs();

            $form2->add('bloc', EntityType::class, [
                'class' => Blocs::class,
                'placeholder' => '',
                'choices' => $positions2,
                'label' => false,
                'choice_label' => function ($category) {
                    return $category->getNom();
                },
            ]);
        };

 

        $builder->get('classes')->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event2) use ($formModifier2) {
                // It's important here to fetch $event->getForm()->getData(), as
                // $event->getData() will get you the client data (that is, the ID)
                $sport2 = $event2->getForm()->getData();

                // since we've added the listener to the child, we'll have to pass on
                // the parent to the callback functions!
                $formModifier2($event2->getForm()->getParent(), $sport2);
            }
        );


    

   
        
    }


}