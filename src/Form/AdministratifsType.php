<?php

namespace App\Form;

use App\Entity\Administratifs;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

class AdministratifsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('type',ChoiceType::class, [
                'choices' => [
                    'Certificat de scolarité' => 'certificat de scolarité',
                    'Catégorie 2' => 'Catégorie 2',
                    'Catégorie 3' => 'Catégorie 3',
                    
                ],
                'expanded' => false,
                'multiple' => false,
                'required' => false,
                'label' => 'Catégorie de document' 
            ])
            ->add('files',FileType::class,[
                'label'=> 'Documents',
                'multiple' => false,
                'mapped'=> false,
                'required'=> false,
        
            
            ])
            ->remove('created_at')
            ->remove('created_by')
           
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Administratifs::class,
        ]);
    }
}
