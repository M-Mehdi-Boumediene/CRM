<?php

namespace App\Form;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Doctrine\ORM\EntityRepository;
use App\Entity\Classes;
use App\Entity\Blocs;
use App\Entity\Modules;
use App\Entity\Users;
use App\Entity\Absences;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
class AbsintervenantsType extends AbstractType
{
    private $em;

    private $tokenStorage;
    public function __construct(TokenStorageInterface   $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        
            $builder

            ->add('classe', EntityType::class, [
                'class' => Classes::class,
                'expanded' => false,
                'multiple' => false,
                'choice_label' => 'nom',
                'empty_data'=>'',
                'required'=>false,
                'label'=>'Classe',
            ])
         
            ->add('tableau', CollectionType::class, [
                'entry_type' => TableauAbsencesintervenantsType::class,
                'entry_options' => ['label' => false],
                'allow_add' => true,
                'prototype' => true,
      
           
                'by_reference' => false,
                'label' => false
                
            ])
               
                
            ;
        
   
            }

   
}
