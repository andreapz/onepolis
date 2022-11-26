<?php

namespace App\Form\Type;

use App\Entity\Event;
use App\Form\Type\AddressType;
use App\Form\Type\DateTimePickerType;
use App\Form\Type\RestaurantType;
use App\Form\Type\TicketCostType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class EventType extends AbstractType
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

        $builder
            ->add('title', null, [
                'attr' => ['autofocus' => true],
                'label' => 'label.title',
            ])
            ->add('initialDate', DateTimePickerType::class, [
                'label' => 'label.initialDate',
                'format' => 'dd-MM-yyyy',
                'help' => 'Set the initial event date.',
            ])
            ->add('endDate', DateTimePickerType::class, [
                'label' => 'label.endDate',
                'format' => 'dd-MM-yyyy',
            ]) 
            ->add('address', AddressType::class)
            ->add('tickets', CollectionType::class, [
                'entry_type' => TicketCostType::class,
                'entry_options' => array('label' => false),
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
            ])
            ->add('restaurants', CollectionType::class, [
                'entry_type' => RestaurantType::class,
                'entry_options' => array('label' => false),
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
            ])
            ->add('hotels', CollectionType::class, [
                'entry_type' => HotelType::class,
                'entry_options' => array('label' => false),
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
            ])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}
