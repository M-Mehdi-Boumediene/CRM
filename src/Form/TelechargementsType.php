<?php

namespace App\Form;

use App\Entity\Telechargements;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\Modules;
use App\Entity\Formations;
use App\Entity\Medias;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Doctrine\ORM\EntityRepository;
use App\Entity\Blocs;
use App\Entity\Classes;
use App\Entity\Etudiants;
use App\Entity\Intervenants;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class TelechargementsType extends AbstractType
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
                    'Devoir' => 'Devoir',
                    ' Projet pro' => ' Projet pro',
                    
                ],
                'expanded' => false,
                'multiple' => false,
                'required' => false,
                'label' => 'Type' 
            ])
      
            


        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Telechargements::class,
        ]);
    }
}
