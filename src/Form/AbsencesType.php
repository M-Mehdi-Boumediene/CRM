<?php

namespace App\Form;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;
use App\Entity\Classes;
use App\Entity\Blocs;
use App\Entity\Modules;
use App\Entity\Users;
use App\Entity\Absences;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AbsencesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder

        ->add('classe', EntityType::class, [
            'class' => Classes::class,
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('u')
                    ->orderBy('u.nom', 'ASC');
            },
            'choice_label' => 'nom',
            'multiple'=>false
        ])
        ->add('module', EntityType::class, [
            'class' => Modules::class,
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('u')
                    ->orderBy('u.nom', 'ASC');
            },
            'choice_label' => 'nom',
            'multiple'=>false
        ])
        ->add('du',DateTimeType::class,[
            'label' => 'Date début',
            'widget' => "single_text"
        ])
        ->add('au',DateTimeType::class,[
            'label' => 'Date fin',
            'widget' => "single_text"
            
        ])
            ->add('user', EntityType::class, [
                'class' => Users::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->orderBy('u.email', 'ASC')
                        ->where('u.roles LIKE :role')
                        ->orWhere('u.roles LIKE :role2')
                            ->setParameter('role','%"'.'ROLE_ETUDIANT'.'"%')
                            ->setParameter('role2','%"'.'ROLE_INTERVENANT'.'"%')
                        ;
                },
                'choice_label' => 'email',
                'multiple'=>false
            ])
           
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Absences::class,
        ]);
    }
}
