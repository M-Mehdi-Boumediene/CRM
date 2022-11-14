<?php

namespace App\Form;

use App\Entity\Cv;
use App\Entity\Etudiants;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\Intervenants;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class CvType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('type',ChoiceType::class, [
            'choices' => [
                'Formation' => 'Formation',
                'Expérience' => 'Expérience',
                
            ],
            'expanded' => false,
            'multiple' => false,
            'required' => false,
            'label' => 'Type' 
        ])
            ->add('entreprise',TextType::class,[
                'label'=> 'Entreprise',
                'required'=>false,
            ])
            ->add('ecole',TextType::class,[
                'label'=> 'Ecole',
                'required'=>false,
            ])
            ->add('formation',TextType::class,[
                'label'=> 'Formation',
                'required'=>false,
            ])
            ->add('debut',DateType::class,[
                'label' => 'Date début',
                'widget' => "single_text",
                'empty_data' => null,
            ])
            ->add('fin',DateType::class,[
                'label' => 'Date fin',
                'widget' => "single_text",
                'empty_data' => null,
            ])
            ->add('titre',TextType::class,[
                'label'=> 'Poste',
                'required'=>false,
            ])

            ->add('description',TextareaType::class,[
                'label'=>'Description',
                'required' => false,
                
            ])


        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Cv::class,
        ]);
    }
}