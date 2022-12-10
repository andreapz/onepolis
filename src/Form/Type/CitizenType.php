<?php

namespace App\Form\Type;

use App\Entity\Branch;
use App\Entity\Bus;
use App\Entity\Citizen;
use App\Entity\RestaurantMeal;
use App\Entity\Relationship;
use App\Entity\Room;
use App\Entity\TicketType;
use App\Form\Type\AddressType;
use App\Form\Type\DateTimePickerType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

class CitizenType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // For the full reference of options defined by each form field type
        // see http://symfony.com/doc/current/reference/forms/types.html

        // By default, form fields include the 'required' attribute, which enables
        // the client-side form validation. This means that you can't test the
        // server-side validation errors from the browser. To temporarily disable
        // this validation, set the 'required' attribute to 'false':
        // $builder->add('title', null, ['required' => false, ...]);

        $builder->add('name', null, [
                'attr' => ['autofocus' => true],
                'label' => 'label.name',
            ]);
        $builder->add('surname', null, [
                'label' => 'label.surname',
            ]);
        $builder->add('cityBirth', null, [
                'label' => 'label.cityBirth',
                'attr' => ['class' => 'c-cityBirth'],
            ]);
        // $builder->add('birthDate', DateTimePickerType::class, [DateType
        //         'label' => 'label.birthDate',
        //         'format' => 'dd/MM/yyyy',
        //     ]);
        $builder->add('birthDate', DateTimePickerType::class, [ 
                'label' => 'label.birthDate',
                'widget' => 'single_text',
                'html5' => false,
                'attr' => ['class' => 'js-datepicker'],
            ]);
        $builder->add('phone', null, [
                'label' => 'label.phone',
            ]);
        $builder->add('email', null, [
                'label' => 'label.email',
            ]);    
        $builder->add('gender', ChoiceType::class, array(
                'choices'  => array(
                    ' ' => null,
                    'M' => 'M',
                    'F' => 'F',
                ),
                'label' => 'label.gender',
            ));
        $builder->add('needSupport', CheckboxType::class, array(
            'label'    => 'label.needSupport',
            'required' => false,
        ));
        $builder->add('delegate', null, [
                'label' => 'label.delegate',
            ]); 
        $builder->add('note', TextareaType::class, array(
                'attr' => array('class' => 'tinymce'),
                'label' => 'label.note',
                'required' => false,
            ));
        
        $builder->add('roomNote', TextareaType::class, array(
                'attr' => array('class' => 'tinymce'),
                'label' => 'label.roomNote',
                'required' => false,
            ));

        $builder->add('address', AddressType::class);
        
        $builder->add('tickettypes', EntityType::class, [
            'class' => TicketType::class,
            'choices' => $options['tickettypes'],
            'choice_label' => function (TicketType $rc) {
                return $rc->getName();
            },
            'multiple' => false,
            'mapped' => false,
            'expanded' => false,
            'label' => "Biglietti",
            /*'group_by' => function($val, $key, $index) {
                return $val->getName();
            },*/
        ]);
            
        $builder->add('meals', EntityType::class, [
            'class' => RestaurantMeal::class,
            'choices' => $options['meals'],
            'choice_label' => function (RestaurantMeal $rc) {
                
                return $rc->getName() . " " . $rc->getMealDate()->format('d/m/Y') ;
            },
            /*'choice_value' => function (RestaurantCost $entity) {
                if($entity && ($entity->getId() == 2)) {
                    return $entity->getId();
                }
                return 'n';
            },*/
            'multiple' => true,
            'mapped' => false,
            'expanded' => true,
            'label' => "Pasti",
            'group_by' => function($val, $key, $index) {
                return $val->getId();
            },
        ]);
            
        $builder->add('buses', EntityType::class, [
            'class' => Bus::class,
            'choices' => $options['buses'],
            'choice_label' => function (Bus $rc) {
                return $rc->getName();
            },
            /*'choice_value' => function (RestaurantCost $entity) {
                if($entity && ($entity->getId() == 2)) {
                    return $entity->getId();
                }
                return 'n';
            },*/
            'multiple' => true,
            'mapped' => false,
            'expanded' => true,
            'label' => "Trasporto",
        ]);
            
        $builder->add('rooms', EntityType::class, [
            'class' => Room::class,
            'choices' => $options['rooms'],
            'choice_label' => function (Room $rc) {
                return $rc->getName();
            },
            /*'choice_value' => function (RestaurantCost $entity) {
                if($entity && ($entity->getId() == 2)) {
                    return $entity->getId();
                }
                return 'n';
            },*/
            'multiple' => false,
            'mapped' => false,
            'expanded' => false,
            'label' => "Pernottamento",
        ]);
        $builder->add('branch', ChoiceType::class, array(
            'choices'  => $options['branches'],
            'label' => 'label.branches',
            'choice_label' => function (Branch $rc) {
                return $rc->getName();
            },
        ));
        $builder->add('relationship', ChoiceType::class, array(
            'choices'  => $options['relationships'],
            'label' => 'label.relationships',
            'choice_label' => function (Relationship $rc) {
                return $rc->getName();
            },
        ));
            
        $builder->add('partner', CheckboxType::class, array(
            'label'    => 'label.partner',
            'required' => false,
        ));
        
        $builder->add('first', CheckboxType::class, array(
            'label'    => 'label.first',
            'required' => false,
        ));
            
        $builder->add('guest', CheckboxType::class, array(
            'label'    => 'label.guest',
            'required' => false,
        ));
        
        $builder->add('transport', CheckboxType::class, array(
            'label'    => 'label.transport',
            'required' => false,
        ));
            
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Citizen::class,
            'tickettypes' => array(),
            'meals' => array(),
            'rooms' => array(),
            'buses' => array(),
            'branches' => array(),
            'relationships' => array(),
            'delegates' => array(),
        ]);
    }
}
