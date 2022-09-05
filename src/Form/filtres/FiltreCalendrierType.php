<?php

namespace App\Form;

use App\Entity\Classes;
use App\Entity\Etudiants;
use App\Entity\Intervenants;
use App\Entity\Modules;

use App\Entity\Blocs;
use App\Entity\Notes;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use App\Form\DataTransformer\ClassesToNumbersTransformer;
class FiltreCalendrierType extends AbstractType
{
    private $em;

    
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
            'label'=>false,
            'empty_data'=>'',
            'multiple' => false,
            'required' => false
        ])
      
        ->add('intervenant', EntityType::class, [
            'class' => Intervenants::class,
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('u')
                    ->orderBy('u.nom', 'ASC');
            },
         
            'choice_label' => function($intervenant, $key, $index) {
                /** @var Intervenants $intervenant */
                return $intervenant->getNom() . ' ' . $intervenant->getPrenom();
            },

            'label'=>false,
            'empty_data'=>'',
            'multiple' => false,
            'required' => false
        ])
        ->add('apprenant', EntityType::class, [
            'class' => Etudiants::class,
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('u')
                    ->orderBy('u.nom', 'ASC');
            },
            'choice_label' => function($apprenant, $key, $index) {
                /** @var Apprenants $apprenant */
                return $apprenant->getNom() . ' ' . $apprenant->getPrenom();
            },
            'label'=>false,
            'empty_data'=>'',
            'multiple' => false,
            'required' => false
        ])

        ;

   

   
        
    }


}