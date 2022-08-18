<?php

namespace App\Form;

use App\Entity\Modules;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormInterface;
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

class ModulesType extends AbstractType
{
    private $em;

public function __construct(EntityManagerInterface $entityManager)
{
    $this->em = $entityManager;
}
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->remove('created_at')
            ->remove('created_by')

            ->add('classes', EntityType::class, [
                'class' => Classes::class,
            
                'choice_label' => 'nom',
                'empty_data'=>'',
                'required'=>false,
         
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

               
            $builder->get('classes')->addEventListener(
                FormEvents::POST_SUBMIT,
                function (FormEvent $event) {
                    $form = $event->getForm();

                    $form ->getParent()->add('bloc', EntityType::class, [
                        'class' => Blocs::class,
                        'choice_label' => 'nom',
                        'choices' => $form->getData()->getBlocs(),
            
                        'required'=>true
                        
                    ]);
    
                }
            );

            $builder->addEventListener(
                FormEvents::POST_SET_DATA,
                function (FormEvent $event) {
                    $form = $event->getForm();
                    $data = $event->getData();
                    $blocs = $data->getBloc();

                    if($blocs){
                        $form->get('classes')->setData($blocs->getClasse());

                        $form->add('bloc', EntityType::class, [
                            'class' => Blocs::class,
                            'choice_label' => 'nom',
                            'attr' => ['class' => 'bloc'],
                            'choices' => $blocs->getClasse()->getBlocs(),
                            'required'=>true
                            
                        ]);
                    }else{

                        
                        $form->add('bloc', EntityType::class, [
                            'class' => Blocs::class,
                            'choice_label' => 'nom',
                            'attr' => ['class' => 'bloc'],
                            'choices' => [],
                            'required'=>true
                            
                        ]);

                    }
            

    
                }
            );

    
 
     
    }
         

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Modules::class,
        ]);
    }
}
