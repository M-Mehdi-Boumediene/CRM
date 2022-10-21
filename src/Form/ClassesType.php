<?php

namespace App\Form;

use App\Entity\Classes;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ClassesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom',TextType::class,[
                'label' =>  false

            ])
            ->add('curus',ChoiceType::class, [
                'choices' => [
                    'Initiale ' => 'Initiale ',
                    'Alternance ' => 'Alternance ',
                    
                ],
                'expanded' => false,
                'multiple' => false,
                'required' => false,
                'label' => 'Cursus' 
            ])
            ->add('Annee_Scolaire')

            ->add('nbsemestre',ChoiceType::class, [
                'choices' => [
                    '1' => '1',
                    '2' => '2',
                    '3' => '3',
                    '4' => '4',
                ],
                'expanded' => false,
                'multiple' => false,
                'required' => false,
                'label' => 'Nombre de semestres' 
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
