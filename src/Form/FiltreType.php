<?php

namespace App\Form;

use App\Entity\Etudiants;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;
use App\Entity\Modules;
use App\Entity\Classes;
use App\Entity\Tuteurs;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use App\Entity\Intervenants;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use App\Entity\Codepostal;
use App\Entity\Villes;
use App\Form\UsersType;
class FiltreType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('apprenant', EntityType::class, [
            'class' => Etudiants::class,
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('u')
                    ->orderBy('u.email', 'ASC');
            },
            'choice_label' => 'email',
            'multiple' => false,
            'required' => false
        ])
 
        ->add('intervenants', EntityType::class, [
            'class' => Intervenants::class,
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('u')
                    ->orderBy('u.email', 'ASC');
            },
            'choice_label' => 'email',
            'multiple' => false,
            'required' => false
        ])
 

   
    ;


 
    


   
    }


}
