<?php

namespace App\Form;

use App\Entity\Cours;
use App\Entity\Modules;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;
use PhpParser\Node\Stmt\Label;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
class CoursType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom',TextType::class, [
                'label' => 'Nom du cours'
            ])
            ->add('module',EntityType::class, [
                'class' => Modules::class,
                'label' => false,
                'choice_label' => 'nom',
                'empty_data'=>'',
                'required'=>false,
         
            ])
           
            ->add('description',CkEditorType::class, [

               
                ])
            
       
         
                ->add('files',FileType::class,[
                    'label'=> 'Documents',
                    'multiple' => true,
                    'mapped'=> false,
                    'required'=> false,
                    
            
                
                ])
               
                ->add('documents',FileType::class,[
                    'label'=> 'Video (mp4)',
                    'multiple' => true,
                    'mapped'=> false,
                    'required'=> false,
                  
            
                
                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Cours::class,
        ]);
    }
}
