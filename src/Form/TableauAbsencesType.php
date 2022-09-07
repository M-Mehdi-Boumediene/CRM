<?php

namespace App\Form;

use App\Entity\TableauAbsences;
use App\Entity\Etudiants;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TableauAbsencesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('dateabsence',DateType::class,[
                'label' => false,
                'widget' => "single_text",
        
                'empty_data' => false,
                 'required'=>false,
            ])
            ->add('retard',TimeType::class,[
                'label' => false,
                'widget' => "single_text",
                'empty_data' => false,
           
                'required'=>false,
            ])
            ->add('etudiant', EntityType::class, [
                'class' => Etudiants::class,
          
                'choice_label' => function($apprenant, $key, $index) {
                    /** @var Apprenants $apprenant */
                    return $apprenant->getPrenom() . ' ' . $apprenant->getNom();
                },
                'required'=>true,
                'expanded'=>false,
                'multiple' => true,
                'label'=>false,
               
                
         
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'validation_groups' => false,
        ]);
    }
}
