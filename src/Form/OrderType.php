<?php

namespace App\Form;

use App\Entity\Address;
use App\Entity\Customer;
use App\Entity\Order;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('number')
            ->add('type',ChoiceType::class, [
                'choices'  => [
                    'Delivery' => "Delivery",
                    'Collection' => "Collection",
                    'Pickup & Delivery' => "Pickup & Delivery",
                ],
            ])
            ->add('date', DateType::class, [
                'mapped' => false,
                'widget' => 'single_text', 
            ])
            ->add('barcode')
            ->add('shipFrom', WarehouseAutocompleteField:: class)
            ->add('shipper', ShipperAutocompleteField::class)
            ->add('customerId', CustomerAutocompleteField::class)
            ->add('addressZone')
            ->add('note')
            ->add('serviceTime')
            ->add('cod')
            ->add('priority', ChoiceType::class, [
                'choices'  => [
                    'High' => "HIGHT",
                    'Normal' => "NORMAL",
                    'Low' => "LOW",
                ],
            ])
            ->add('weight', NumberType::class)
            ->add('volume', NumberType::class)
            ->add('pkg', NumberType::class)

            ->add('orderItems', CollectionType::class, [
                'entry_type' => OrderItemType::class,
                'label' => ' ',
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
            ])
        ;

        $formModifier = function (FormInterface $form, Customer $customer = null): void {
            $addresses = (null === $customer) ? [] : $customer->getAddresses();

            $form->add('addressId', EntityType::class, [
                'class' => Address::class,
                'placeholder' => '',
                'choices' => $addresses,
            ]);

            $custContact=  (null === $customer) ? "" : $customer->getContact();
            $form->add('contactName', null,[
                'empty_data' => $custContact
            ]);

            $custEmail=  (null === $customer) ? "" : $customer->getEmail();
            $form->add('customerEmail', null,[
                'empty_data' => $custEmail
            ]);

            $custPhone =  (null === $customer) ? "" : $customer->getPhone();
            $form->add('customerPhone', null,[
                'empty_data' => $custPhone
            ]);

            $custTimeFrom =  (null === $customer) ? "" : $customer->getTimeFrom()->format('H:i:a');
            $form->add('timeFrom', null , [
                'mapped' => false,
                'widget' => 'single_text', 
                'html5' => false,
                'empty_data'  => $custTimeFrom,
            ]);

            $custTimeUntil =  (null === $customer) ? "" : $customer->getTimeUntil()->format('H:i:a');
            $form->add('timeUntil', null , [
                'mapped' => false,
                'widget' => 'single_text', 
                'html5' => false,
                'empty_data'  => $custTimeUntil,
            ]);
        };

        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $event) use ($formModifier): void {
                $orderData = $event->getData();
                $formModifier($event->getForm(), $orderData->getCustomerId());
            }
        );

        $builder->get('customerId')->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) use ($formModifier): void {                
                $customerData = $event->getForm()->getData();
                $form = $event->getForm()->getParent();

                // since we've added the listener to the child, we'll have to pass on
                // the parent to the callback function!
                $formModifier($form, $customerData);
            }
        );

        // by default, action does not appear in the <form> tag
        // you can set this value by passing the controller route
        $builder->setAction($options['action']);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Order::class,
        ]);
    }
}
