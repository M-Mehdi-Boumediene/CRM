<?php

namespace App\Form;

use App\Entity\Intervenants;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;
use App\Entity\Modules;
use App\Entity\Classes;
use App\Entity\Etudiants;
use App\Entity\Codepostal;
use App\Entity\Villes;
use App\Form\UsersType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;

class IntervenantsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom',TextType::class,[
                
                'label'=>false,
            ])
            ->add('prenom',TextType::class,[
                
                'label'=>false,
            ])
            ->add('adresse',TextType::class,[
                
                'label'=>false,
            ])
            ->add('telephone',TelType::class,[
                
                'label'=>false,
            ])
            ->remove('email',EmailType::class,[
                
                'label'=>false,
            ])
            ->add('classes',EntityType::class, [
                'class' => Classes::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->orderBy('u.nom', 'ASC');
                },
                'choice_label' => 'nom',
                'label'=>false,
                'placeholder'=>'',
                'autocomplete' => true,
            ])

            ->remove('ville', EntityType::class, [
                'mapped' => false,
                'class' => Villes::class,
                'choice_label' => 'nom',
                'placeholder' => '',
                'label' => false,
                'required' => false,
              
            ])
     

            ->remove('created_at')
            ->remove('created_by')

            ->add('modules', EntityType::class, [
                'mapped' => false,
                'class' => Modules::class,
                'choice_label' => 'nom',
                'placeholder' => '',
                'label' => false,
                'autocomplete' => true,
                'required' => false
            ])
            ->add('apprenants', EntityType::class, [
                'mapped' => false,
                'class' => Etudiants::class,
                'choice_label' => function ($category) {
                    return $category->getNom() . ' ' . $category->getPrenom();
                },
                'placeholder' => ' Apprenants',
                'label' => false,
                'multiple' => true,
                'autocomplete' => true,
                'required' => false
            ])
            ->add('user', UsersType::class)
            ->add('cat',ChoiceType::class, [
                'choices' => [
                    'Intervenant permanent' => 'Intervenant permanent',
                    'Intervenant remplaçant' => 'Intervenant remplaçant',
                    
                ],
                'expanded' => false,
                'multiple' => false,
                'required' => false,
                'label' => false,
            ])

            ->add('istuteur', CheckboxType::class, [
                'label'    => 'Tuteur Pédagogique',
                'required' => false,
            ])
            
          
       ;
       $formModifier = function (FormInterface $form, Classes $sport = null) {
        $positions = null === $sport ? [] : $sport->getModules();
        $positions2 = null === $sport ? [] : $sport->getIntervenants();
        $form->add('modules', EntityType::class, [
            'class' => Modules::class,
            'mapped' => false,
            'autocomplete' => true,
            'choice_label' => function ($category) {
                return $category->getNom() ;
            },
            'placeholder' => '',
            'label' => false,
            'choices' => $positions,
        ]);

  
    };

    $builder->addEventListener(
        FormEvents::PRE_SET_DATA,
        function (FormEvent $event) use ($formModifier) {
            // this would be your entity, i.e. SportMeetup
            $data = $event->getData();

            $formModifier($event->getForm(), $data->getClasses());
        }
    );






    $builder->addEventListener(
        FormEvents::PRE_SET_DATA,
        function (FormEvent $event) use ($formModifier) {
            // this would be your entity, i.e. SportMeetup
            $data = $event->getData();

            $formModifier($event->getForm(), $data->getClasses());
        }
    );

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



    

    
    
        }

    
   

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Intervenants::class,
        ]);
    }
}
