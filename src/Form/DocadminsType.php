<?php

namespace App\Form;
use App\Entity\Modules;
use App\Entity\Formations;
use App\Entity\Classes;
use App\Entity\Users;
use App\Entity\Medias;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

use App\Entity\Docadmins;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DocadminsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
 

            ->add('nom',TextType::class,[
                'label'=>'Nom du fichier'
            ])
          
            ->add('files',FileType::class,[
                'label'=> 'Fichier',
                'multiple' => true,
                'mapped'=> false,
                'required'=> false,
        
              

            ])

            ->add('type',ChoiceType::class, [
                'choices' => [
                    'Document administratif' => 'Document administratif',
                    'Document indispensable' => 'Document indispensable',
                    
                ],
                'expanded' => false,
                'multiple' => false,
                'required' => false,
                'label' => 'Type' 
            ])
            ->add('classe', EntityType::class, [
                'class' => Classes::class,
                'label' => false,
                'choice_label' => 'nom',
                'empty_data'=>'',
                'required'=>false,
         
            ])
            

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Docadmins::class,
        ]);
    }
}
