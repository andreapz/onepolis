<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Form\Type;

use App\Entity\Event;
use App\Entity\Task;
use App\Entity\RestaurantMeal;
use App\Entity\Room;
use App\Entity\TicketType;
use App\Form\Type\CitizenType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class TaskType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('citizens', CollectionType::class, [
                'entry_type' => CitizenType::class,
                'entry_options' => array('label' => false),
            ]);
        
        $builder->add('tickets', EntityType::class, [
            'class' => TicketType::class,
            'choices' => $options['meals'],
            'choice_label' => function (TicketType $rc) {
                return $rc->getName() . " " . $rc->getDays();
            },
            'multiple' => true,
            'mapped' => false,
            'expanded' => true,
            'label' => false,
            'group_by' => function($val, $key, $index) {
                return $val->getDateFormatted();
            },
        ]);
            
        $builder->add('meals', EntityType::class, [
            'class' => RestaurantMeal::class,
            'choices' => $options['meals'],
            'choice_label' => function (RestaurantMeal $rc) {
                return $rc->getName() . " " . $rc->getMealDate()->format('d/m/Y');
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
            'label' => false,
            'group_by' => function($val, $key, $index) {
                return $val->getDateFormatted();
            },
        ]);
        $builder->add('rooms', EntityType::class, [
            'class' => Room::class,
            'choices' => $options['rooms'],
            'choice_label' => function (Room $rc) {
            return $rc->getName() . " [" . $rc->getDays() . "] giorni";
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
            'label' => "Pernottamento",
            ]);
        
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Task::class,
            'meals' => array(),
            'rooms' => array(),
            'tickets' => array()
        ));
    }
}
