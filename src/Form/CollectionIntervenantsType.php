<?php

namespace App\Form;

use App\Entity\Modules;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Doctrine\ORM\EntityRepository;
use App\Entity\Blocs;
use App\Entity\Classes;
use App\Entity\Etudiants;
use App\Entity\Intervenants;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\Extension\Core\Type\TextType;
class CollectionIntervenantsType extends AbstractType
{
    private $em;

public function __construct(EntityManagerInterface $entityManager)
{
    $this->em = $entityManager;
}
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder


            ->add('intervenants', EntityType::class, [
                'class' => Intervenants::class,
                'label' => false,
                'choice_label' => 'nom',
                'empty_data'=>'',
                'required'=>false,
         
            ])
           

           
     
            ;

               
  

      
        

    
 
     
    }
         


}
