<?php

namespace App\Form\Type;

use App\Entity\RestaurantRealMealPrice;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class RestaurantRealMealPriceType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('price', null, [
                'attr' => ['autofocus' => true],
                'label' => 'room.price',
            ])
            ->add('guests', null, [
                'attr' => ['autofocus' => true],
                'label' => 'room.guests',
            ])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => RestaurantRealMealPrice::class,
        ]);
    }
}
