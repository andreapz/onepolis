<?php

namespace App\Form\Type;

use App\Entity\RestaurantExtraCost;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class RestaurantExtraCostType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('price', null, [
                'attr' => ['autofocus' => true],
                'label' => 'extracost.price',
            ])
            ->add('type', ChoiceType::class, array(
                'choices'  => array(
                    ' ' => null,
                    'Pulizia' => 'Pulizia',
                ),
                'label' => 'extracost.type',
            ))
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => RestaurantExtraCost::class,
        ]);
    }
}
