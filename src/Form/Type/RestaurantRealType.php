<?php

namespace App\Form\Type;

use App\Entity\Restaurant;
use App\Entity\RestaurantReal;
use App\Form\Type\AddressType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class RestaurantRealType extends AbstractType
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
            ->add('surname', null, [
                'label' => 'label.surname',
            ])
            ->add('phone', null, [
                'label' => 'label.phone',
            ])
            ->add('email', null, [
                'label' => 'label.email',
            ])
            ->add('latitude', null, [
                'label' => 'label.latitude',
            ])
            ->add('longitude', null, [
                'label' => 'label.longitude',
            ])
            ->add('address', AddressType::class)
            ->add('note', TextareaType::class, array(
                'attr' => array('class' => 'tinymce'),
                'label' => 'label.note',
                'required' => false,
            ))
            ->add('restaurants', EntityType::class, [
            'class' => Restaurant::class,
                'choices' => $options['restaurants'],
                'choice_label' => function (Restaurant $rc) {
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
                'expanded' => true,
                'label' => "label.restaurant",
                ]);
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => RestaurantReal::class,
            'restaurants' => array(),
        ]);
    }
}
