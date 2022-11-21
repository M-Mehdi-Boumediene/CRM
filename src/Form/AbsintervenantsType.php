<?php

namespace App\Form;

use App\Entity\Absintervenants;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AbsintervenantsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('date')
            ->add('created_at')
            ->add('created_by')
            ->add('du')
            ->add('au')
            ->add('absent')
            ->add('dateabsence')
            ->add('enretard')
            ->add('dateretard')
            ->add('present')
            ->add('datepresence')
            ->add('userid')
            ->add('intervenant')
            ->add('module')
            ->add('classe')
            ->add('user')
            ->add('tableau')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Absintervenants::class,
        ]);
    }
}
