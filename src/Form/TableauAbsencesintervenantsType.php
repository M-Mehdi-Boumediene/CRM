<?php

namespace App\Form;

use App\Entity\TableauAbsences;
use App\Entity\Etudiants;
use App\Entity\Intervenants;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TableauAbsencesintervenantsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder

            ->add('absence', CheckboxType::class, [
                'label'    => 'Absent ',
                'required' => false,
            ])
    
            ->add('dateabsence',DateTimeType::class,[
                'label' => 'Date absence',
                'widget' => "single_text",
                'empty_data' => null,
                'required' => false,
                
            ])
            ->add('presence', CheckboxType::class, [
                'label'    => 'Présent ',
                'required' => false,
            ])
            ->add('enretard', CheckboxType::class, [
                'label'    => 'En retard ',
                'required' => false,
            ])
            ->add('retard',TimeType::class,[
                'label' => false,
                'widget' => "single_text",
                'empty_data' => false,
           
                'required'=>false,
            ])
            ->add('du',DateTimeType::class,[
                'label' => false,
                'widget' => "single_text",
                'empty_data' => false,
           
                'required'=>false,
            ])
            ->add('au',DateTimeType::class,[
                'label' => false,
                'widget' => "single_text",
                'empty_data' => false,
           
                'required'=>false,
            ])
            ->add('intervenant', EntityType::class, [
                'class' => Intervenants::class,
          
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
