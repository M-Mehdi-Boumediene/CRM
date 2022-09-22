<?php

namespace App\Form;

use App\Entity\Calendrier;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ColorType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Doctrine\ORM\EntityRepository;
use App\Entity\Classes;
use App\Entity\Blocs;
use App\Entity\Modules;
use App\Entity\Users;
use App\Entity\Etudiants;
use App\Entity\Intervenants;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormInterface;

class CalendrierType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('titre',ChoiceType::class, [
            'choices' => [
                'COURS' => 'COURS',
                'EXAMEN' => 'EXAMEN',
                'ATELIER' => 'ATELIER',
                'CONFÉRENCE' => 'CONFÉRENCE',
                'DIVERS' => 'DIVERS',
                
            ],
            'expanded' => false,
            'multiple' => false,
            'required' => false,
            'label' => 'Evenement' 
        ])

            ->add('start',DateTimeType::class,[
                'label' => 'Date début',
                'widget' => "single_text",
                'empty_data' => null,
            ])
            ->add('end',DateTimeType::class,[
                'label' => 'Date fin',
                'widget' => "single_text",
                'empty_data' => null,
                
                
            ])
            ->add('description',TextareaType::class,[
                'label'=>'Commentaire',
                'required' => false,
            ])
            ->remove('all_day')
            ->add('background_color', ColorType::class,[
                'label' => 'Couleur du fond ',
                'required' => false,
                
            ])
            ->add('border_color', ColorType::class,[
                'label' => 'Couleur bordure ',
                
                
            ])
            ->add('text_color', ColorType::class,[
                'label' => 'Couleur du texte ',
                'data'=> '#ffffff'
                
                
            ])

            ->add('classe', EntityType::class, [
                'class' => Classes::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->orderBy('u.nom', 'ASC');
                },
                'choice_label' => 'nom',
                'placeholder' => '',
                'multiple'=>false,
                'required' => true,
                'label' => false,
            ])

            ->remove('bloc', EntityType::class, [
                'class' => Blocs::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->orderBy('u.nom', 'ASC');
                },
                'choice_label' => 'nom',
                'multiple'=>false,
                'required' => true,
            ])



            ->add('intervenant', EntityType::class, [
                'class' => Intervenants::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->orderBy('u.nom', 'ASC')
            
                        ;
                },
                'choice_label' => function ($category) {
                    return $category->getNom() . ' ' . $category->getPrenom();
                },
                'multiple'=>false,
                'required' => true,
                'label' => false

            ])


            ->remove('type')
         ->add('date', DateType::class, [
        
                'widget' => 'single_text',
                'label' => 'Date'
            ])

            ->add('heurdebut', TimeType::class, [
        
                'widget' => 'single_text',
                'label' => false
            ])
            ->add('duree', TimeType::class, [
        
                'widget' => 'single_text',
                'label' => false
            ])
            ->remove('heurefin', TimeType::class, [
             
                'widget' => 'single_text',
                'label' => false
            ]);
        ;

        $formModifier = function (FormInterface $form, Classes $sport = null) {
            $positions = null === $sport ? [] : $sport->getModules();
            $positions2 = null === $sport ? [] : $sport->getIntervenants();
            $form->add('module', EntityType::class, [
                'class' => Modules::class,
                'placeholder' => '',
                'label' => false,
                'choices' => $positions,
            ]);

            $form->add('intervenant', EntityType::class, [
                'class' => Intervenants::class,
                'placeholder' => '',
                'choices' => $positions2,
                'label' => false,
                'choice_label' => function ($category) {
                    return $category->getNom() . ' ' . $category->getPrenom();
                },
            ]);
        };

        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $event) use ($formModifier) {
                // this would be your entity, i.e. SportMeetup
                $data = $event->getData();

                $formModifier($event->getForm(), $data->getClasse());
            }
        );

        $builder->get('classe')->addEventListener(
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
            $positions2 = null === $sport2 ? [] : $sport2->getIntervenants();

            $form2->add('intervenant', EntityType::class, [
                'class' => Intervenants::class,
                'placeholder' => '',
                'choices' => $positions2,
                'label' => false,
                'choice_label' => function ($category) {
                    return $category->getNom() . ' ' . $category->getPrenom();
                },
            ]);
        };

        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $event2) use ($formModifier2) {
                // this would be your entity, i.e. SportMeetup
                $data2 = $event2->getData();

                $formModifier2($event2->getForm(), $data2->getClasse());
            }
        );

        $builder->get('classe')->addEventListener(
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

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Calendrier::class,
        ]);
    }
}