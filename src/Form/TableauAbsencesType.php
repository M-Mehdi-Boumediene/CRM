<?php

namespace App\Form;

use App\Entity\TableauAbsences;
use App\Entity\Etudiants;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TableauAbsencesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('dateabsence')
            ->add('retard')
            ->add('etudiant', EntityType::class, [
                'class' => Etudiants::class,
          
                'choice_label' => function($apprenant, $key, $index) {
                    /** @var Apprenants $apprenant */
                    return $apprenant->getNom() . ' ' . $apprenant->getPrenom();
                },
                'required'=>true,
                'expanded'=>false,
                'multiple' => true,
         
                'label'=>false,
                'data_class' => null,
                
         
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => TableauAbsences::class,
        ]);
    }
}
