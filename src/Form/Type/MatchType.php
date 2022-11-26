<?php

namespace App\Form\Type;

//use App\Entity\Hotel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class MatchType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('hotels', ChoiceType::class, array(
                'choices'  => $options['hotels'],
                'choice_label' => function (Hotel $rc) {
                    return $rc->getName();
                },
                'label' => 'label.hotel',
            ))
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            //'data_class' => Hotel::class,
            'hotels' => array(),
        ]);
    }
}
