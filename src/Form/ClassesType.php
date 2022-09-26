<?php

namespace App\Form;

use App\Entity\Classes;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class ClassesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('cursus',ChoiceType::class, [
                'choices' => [
                    'Initiale' => 'Initiale',
                    'Alternance' => 'Alternance',      
                ],
                'expanded' => false,
                'multiple' => false,
                'required' => false,  
                'label'=>'Cursus',  
            ])
            ->add('anneeScolaire',ChoiceType::class, [
                'choices' => [
                    '2022-2023' => '2022-2023',
                    '2023-2024' => '2023-2024',   
                    '2024-2025' => '2024-2025', 
                    '2026-2027' => '2026-2027',
                    '2027-2028' => '2027-2028',
                    '2028-2029' => '2028-2029',  
                    '2029-2030' => '2029-2030', 
                ],
                'expanded' => false,
                'multiple' => false,
                'required' => false,  
                'label'=>'Année Scolaires',  
            ])
            ->add('nombresemestre',ChoiceType::class, [
                'choices' => [
                    'Semestre - 1' => 'Semestre - 1',
                    'Semestre - 2' => 'Semestre - 2',   
                    'Semestre - 3' => 'Semestre - 3', 
                    'Semestre - 4' => 'Semestre - 4', 
                ],
                'expanded' => false,
                'multiple' => false,
                'required' => false,  
                'label'=>'Nombre de Semestre',  
            ])
        
            ->remove('created_at')
            ->remove('created_by')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Classes::class,
        ]);
    }
}
