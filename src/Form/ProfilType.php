<?php

namespace App\Form;

use App\Entity\Profil;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ProfilType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom',TextType::class, [
                'label'=>'Téléphone 2'
            ])
            ->add('prenom' ,TextType::class,[
                'label'=>'Adresse 2'
            ])
            ->add('image',FileType::class,[
                'label'=> ' ',
                'multiple' => false,
                'mapped'=> false,
                'required'=> false,   
                ])
            ->add('adresse', TextType::class,[
                'label'=>'Email 2'
            ])
            ->add('telephone', TextType::class,[
                'label'=>'Age'
            ])        
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Profil::class,
        ]);
    }
}
