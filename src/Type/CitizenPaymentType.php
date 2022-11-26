<?php

namespace App\Form\Type;

use App\Entity\Branch;
use App\Entity\CitizenPayment;
use App\Entity\RestaurantMeal;
use App\Entity\Relationship;
use App\Entity\Room;
use App\Form\Type\AddressType;
use App\Form\Type\DateTimePickerType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;


class CitizenPaymentType extends AbstractType
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

        //$builder->setAction($this->router->generate('admin_citizen_new_payment'));
        
        $builder->add('value', null, [
                'attr' => ['autofocus' => true],
                'label' => 'label.value',
            ]);
        $builder->add('paymentDate', DateTimePickerType::class, [
                'label' => 'label.paymentDate',
                'format' => 'dd/MM/yyyy',
            ]);
        $builder->add('code', null, [
                'label' => 'label.code',
                'required' => false,
            ]);
        $builder->add('type', ChoiceType::class, array(
                'choices'  => array(
                    ' ' => null,
                    'Bonifico' => 'Bonifico',
                    'Contanti' => 'Contanti',
                ),
                'label' => 'label.payment.type',
            ));
        $builder->add('description', TextareaType::class, array(
                'attr' => array('class' => 'tinymce'),
                'label' => 'label.note',
                'required' => false,
            ));
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => CitizenPayment::class,
        ]);
    }
}
