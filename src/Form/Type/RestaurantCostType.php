<?php

namespace App\Form\Type;

use App\Entity\RestaurantCost;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RestaurantCostType extends AbstractType
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
            ->add('name', null, [
                'label' => 'label.name',
            ])
            ->add('price', null, [
                'label' => 'label.price',
            ])
            ->add('minAge', null, [
                'label' => 'label.minAge',
            ])
            ->add('maxAge', null, [
                'label' => 'label.maxAge',
            ])
            ->add('total', null, [
                'label' => 'label.total',
            ])
            ->add('bookInitDate', DateTimePickerType::class, [
                'label' => 'label.bookDate',
                'widget' => 'single_text',
                'html5' => false,
                'attr' => ['class' => 'js-datepicker'],
            ])
            ->add('bookEndDate', DateTimePickerType::class, [
                'label' => 'label.bookDate',
                'widget' => 'single_text',
                'html5' => false,
                'attr' => ['class' => 'js-datepicker'],
            ])
            ->add('type', null, [
                'label' => 'label.type',
            ])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => RestaurantCost::class,
        ]);
    }
}
