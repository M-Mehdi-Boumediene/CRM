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

class NotesType extends AbstractType
{
    private $em;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
         
            ->add('moduleid', EntityType::class, [
                'class' => Modules::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->orderBy('u.nom', 'ASC');
                },
                'choice_label' => 'nom',
              
            ])
            ->add('classes', EntityType::class, [
                'class' => Classes::class,
            
                'choice_label' => 'nom',
                'empty_data'=>'',
                'required'=>false,
         
            ])
            ->add('blocid', EntityType::class, [
                'class' => Blocs::class,
            
                'choice_label' => 'nom',
                'empty_data'=>'',
                'required'=>false,
         
            ])
      
            ->remove('apprenant', EntityType::class, [
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
           
            ->remove('intervenant', EntityType::class, [
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

        $builder->get('classes')->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) {
                $form = $event->getForm();

                $form ->getParent()->add('blocid', EntityType::class, [
                    'class' => Blocs::class,
                    'choice_label' => 'nom',
                    'choices' => $form->getData()->getBlocs(),
        
                    'required'=>true
                    
                ]);

            }
        );

        $builder->get('blocid')->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) {
                $form = $event->getForm();

                $form ->getParent()->add('moduleid', EntityType::class, [
                    'class' => Modules::class,
                    'choice_label' => 'nom',
                    'choices' => $form->getData()->getModules(),
        
                    'required'=>true
                    
                ]);

            }
        );

        $builder->addEventListener(
            FormEvents::POST_SET_DATA,
            function (FormEvent $event) {
                $form = $event->getForm();
                $data = $event->getData();
                $blocs = $data->getBlocid();

                if($blocs){
                    $form->get('classes')->setData($blocs->getClasse());

                    $form->add('blocid', EntityType::class, [
                        'class' => Blocs::class,
                        'choice_label' => 'nom',
                        'attr' => ['class' => 'bloc'],
                        'choices' => $blocs->getClasses()->getBlocs(),
                        'required'=>true
                        
                    ]);
                    
                }else{

                    
                    $form->add('blocid', EntityType::class, [
                        'class' => Blocs::class,
                        'choice_label' => 'nom',
                        'attr' => ['class' => 'bloc'],
                        'choices' => [],
                        'required'=>true
                        
                    ]);

                }
        


            }
        );

        $builder->addEventListener(
            FormEvents::POST_SET_DATA,
            function (FormEvent $event) {
                $form = $event->getForm();
                $data = $event->getData();
                $module = $data->getModuleid();

                if($module){
                    $form->get('blocid')->setData($module->getModules());

                    $form->add('moduleid', EntityType::class, [
                        'class' => Modules::class,
                        'choice_label' => 'nom',
                        'attr' => ['class' => 'module'],
                        'choices' => $module->getBloc()->getModule(),
                        'required'=>true
                        
                    ]);
                    
                }else{

                    
                    $form->add('blocid', EntityType::class, [
                        'class' => Blocs::class,
                        'choice_label' => 'nom',
                        'attr' => ['class' => 'bloc'],
                        'choices' => [],
                        'required'=>true
                        
                    ]);

                }
        


            }
        );
        
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Notes::class,
        ]);
    }
}