<?php

namespace App\Form;

use App\Entity\Tuteurs;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;
use App\Entity\Entreprises;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
class TuteursType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom',TextType::class)
            ->add('prenom',TextType::class)
            ->add('adresse',TextType::class)
            ->add('telephone',TelType::class)
            ->add('email',EmailType::class)
            ->add('type',ChoiceType::class, [
                'choices' => [
                    'Tuteur pédagogique' => 'Tuteur pédagogique',
                    'Tuteur entreprise' => 'Tuteur entreprise',
                    
                ],
                'expanded' => false,
                'multiple' => false,
                'required' => false,
                'label' => 'Type' 
            ])
            ->add('entreprise', EntityType::class, [
                'class' => Entreprises::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->orderBy('u.nom', 'ASC');
                },
                'choice_label' => 'nom',
                'multiple' => false,
                'required' => false
            ])
            ->remove('created_at')
            ->remove('created_by');

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Tuteurs::class,
        ]);
    }
}
