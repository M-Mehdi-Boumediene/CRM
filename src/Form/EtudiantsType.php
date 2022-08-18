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
class EtudiantsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('nom',TextType::class,[
                
            'label'=>false,
        ])
        ->add('prenom',TextType::class,[
            
            'label'=>false,
        ])
        ->add('adresse',TextType::class,[
            
            'label'=>false,
        ])
        ->add('telephone',TelType::class,[
            
            'label'=>false,
        ])
        ->remove('email',EmailType::class,[
            
            'label'=>false,
        ])
        ->add('classes',EntityType::class, [
            'class' => Classes::class,
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('u')
                    ->orderBy('u.nom', 'ASC');
            },
            'choice_label' => 'nom',
            'label'=>false,
            'placeholder'=>'',
            'autocomplete' => true,
        ])

        ->add('ville', EntityType::class, [
            'mapped' => false,
            'class' => Villes::class,
            'choice_label' => 'nom',
            'placeholder' => '',
            'label' => false,
            'required' => false,
          
        ])
 

        ->remove('created_at')
        ->remove('created_by')

        ->remove('modules', EntityType::class, [
            'mapped' => false,
            'class' => Modules::class,
            'choice_label' => 'nom',
            'placeholder' => '',
            'label' => false,
            'autocomplete' => true,
            'required' => false
        ])
        ->add('user', UsersType::class);
   
    ;

    $builder->get('ville')->addEventListener(
        FormEvents::POST_SUBMIT,
        function (FormEvent $event) {
            $form = $event->getForm();

            $form ->getParent()->add('codepostale', EntityType::class, [
                'class' => Codepostal::class,
                'choice_label' => 'nom',
                'choices' => $form->getData()->getCodepostale(),
                'label' => false,
                'required'=>true
                
            ]);

        }
    );

    $builder->addEventListener(
        FormEvents::POST_SET_DATA,
        function (FormEvent $event) {
            $form = $event->getForm();
            $data = $event->getData();
            $code = $data->getCodepostale();

            if($code){
                $form->get('ville')->setData($code->getVilles());

                $form->add('codepostale', EntityType::class, [
                    'class' => Codepostal::class,
                    'choice_label' => 'nom',
                    'choices' => $code->getVilles()->getCodepostale(),
                    'required'=>true,
                    'label' => false,
                
                    
                ]);
            }else{

                
                $form->add('codepostale', EntityType::class, [
                    'class' => Codepostal::class,
                    'choice_label' => 'nom',
                    'choices' => [],
                    'required'=>true,
                    'label' => false,
                    
                ]);

            }
    


        }
    );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Etudiants::class,
        ]);
    }
}
