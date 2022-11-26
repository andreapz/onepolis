<?php

namespace App\Form\Type;

use App\Entity\RoomBase;
use App\Entity\RoomReal;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class RoomRealType extends AbstractType
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
                'label' => 'room.name',
            ])
            ->add('floor', null, [
                'attr' => ['autofocus' => true],
                'label' => 'room.floor',
            ])
            ->add('rooms', null, [
                'label' => 'room.rooms',
            ])
            ->add('guests', null, [
                'label' => 'room.guests',
            ])
            ->add('bath', CheckboxType::class, array(
                'label'    => 'room.bath',
                'required' => false,
            ))
            ->add('accessible', CheckboxType::class, array(
                'label'    => 'room.accessible',
                'required' => false,
            ))
            ->add('single', null, [
                'label' => 'room.single',
            ])
            ->add('double', null, [
                'label' => 'room.double',
            ])
            ->add('twin', null, [
                'label' => 'room.twin',
            ])
            ->add('sofa', null, [
                'label' => 'room.sofa',
            ])
            ->add('bunk', null, [
                'label' => 'room.bunk',
            ])
            ->add('room', EntityType::class, [
                'class' => RoomBase::class,
                'choices' => $options['roomvirtuals'],
                'choice_label' => function (RoomBase $rc) {
                    return $rc->getName();
                },
                'multiple' => false,
                'mapped' => false,
                'expanded' => false,
                'label' => "Camera",
            ])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => RoomReal::class,
            'roomvirtuals' => array()
        ]);
    }
}
