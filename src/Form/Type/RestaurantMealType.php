<?php

namespace App\Form\Type;

use App\Entity\RestaurantMeal;
use App\Form\Type\DateTimePickerType;
use App\Form\Type\RestaurantCostType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class RestaurantMealType extends AbstractType
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
                'attr' => ['autofocus' => true],
                'label' => 'label.name',
            ])
            ->add('mealDate', DateTimePickerType::class, [
                'label' => 'label.mealDate',
                'format' => 'dd-MM-yyyy',
            ])
            ->add('type', null, [
                'label' => 'label.type',
            ])
            ->add('tickets', CollectionType::class, [
                'entry_type' => RestaurantCostType::class,
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
            'data_class' => RestaurantMeal::class,
        ]);
    }
}
