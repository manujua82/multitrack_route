<?php

namespace App\Form;

use App\Entity\Order;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
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
                'widget' => 'single_text', 
                'html5' => false,
            ])
            ->add('barcode')
            ->add('shipFrom', WarehouseAutocompleteField:: class)
            ->add('shipper', ShipperAutocompleteField::class)
            ->add('customerId', CustomerAutocompleteField::class)
            ->add('customerName')
            ->add('contactName')
            ->add('customerEmail')
            ->add('customerPhone')
            ->add('addressZone')
            ->add('timeFrom', null, [
                'widget' => 'single_text', 
                'html5' => false,
            ])
            ->add('timeUntil', null, [
                'widget' => 'single_text', 
                'html5' => false,
            ])
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
            // ->addDependent('addressId')
           
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Order::class,
        ]);
    }
}
