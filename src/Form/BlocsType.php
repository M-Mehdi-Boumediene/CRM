<?php

namespace App\Form;

use App\Entity\Blocs;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;
use App\Entity\Classes;
use Doctrine\DBAL\Types\TextType as TypesTextType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class BlocsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('coefficient',TextType::class, [
               
            ])
            ->remove('created_at')
            ->remove('created_by')
            ->add('Classe', EntityType::class, [
                'class' => Classes::class,
                'label'=>false,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->orderBy('u.nom', 'ASC');
                },
                'choice_label' => 'nom',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Blocs::class,
        ]);
    }
}
