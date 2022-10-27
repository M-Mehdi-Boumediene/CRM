<?php

namespace App\Form;

use App\Entity\Entreprises;


use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EntreprisesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class,[
                'label'=>'Raison sociale'
            ])
            ->add('siret')
            ->add('adresse')
            ->add('telephone')
            ->remove('email')
            ->add('responsable', TextType::class,[
                'label'=>false
            ])
            ->add('emailrepresentant1', TextType::class,[
                'label'=>'Email Représentant 1'
            ])
            ->add('numerotelephone1', TextType::class,[
                'label'=>'Numéro de Téléphone 1'
            ])
            ->add('emailrepresentant2', TextType::class,[
                'label'=>'Email Représentant 2'
            ])
            ->add('numeortelephone2', TextType::class,[
                'label'=>'Numéro de Téléphone 2'
            ])
            ->add('users', UsersType::class)
            ->remove('created_at')
            ->remove('created_by')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Entreprises::class,
        ]);
    }
}
