<?php

namespace App\Form;

use App\Entity\Justifications;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\TableauAbsences;
class JustificationsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('message')
            ->add('files',FileType::class,[
                'label'=> false,
                'multiple' => true,
                'mapped'=> false,
                'required'=> false,
              
        
            
            ])
      
            ->add('tableauabsence', EntityType::class, [
                'class' => TableauAbsences::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->orderBy('u.id', 'ASC');
                },
           
                'label'=>false,
                'multiple' => true,
                'required' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Justifications::class,
        ]);
    }
}
