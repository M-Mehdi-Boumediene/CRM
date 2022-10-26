<?php

namespace App\Form;

use App\Entity\Indispensables;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class IndispensablesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('type',ChoiceType::class, [
                'choices' => [
                    "Livret d'accueil" => "Livret d'accueil",
                    "Plan de l'école" => "Plan de l'école",
                    "Guide de l'altarnant" => "Guide de l'altarnant",
                    "Guide d'handicap" => "Guide d'handicap",     
                ],
                'expanded' => false,
                'multiple' => false,
                'required' => false,
                'label' => 'Catégorie de document' 
            ])
            ->add('files',FileType::class,[
                'label'=> 'Documents',
                'multiple' => false,
                'mapped'=> false,
                'required'=> false,    
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Indispensables::class,
        ]);
    }
}
