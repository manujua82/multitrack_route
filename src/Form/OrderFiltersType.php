<?php

namespace App\Form;

use App\Entity\OrderFilters;
use App\Entity\OrderStatus;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrderFiltersType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('types', ChoiceType::class, [
                'choices'  => [
                    'Delivery' => "Delivery",
                    'Collection' => "Collection",
                    'Pickup & Delivery' => "Pickup & Delivery",
                ],
                'multiple' =>  true,
                'required' => false,
                'autocomplete' => true,
                'attr' => ['class' => 'form-control-sm'],
            ])
            ->add('status', EnumType::class, [
                'class' => OrderStatus::class,
                'multiple' =>  true,
                'autocomplete' => true,
                'required' => false,
                'attr' => ['class' => 'form-control-sm'],
            ])
            ->add('depot', WarehouseAutocompleteField:: class, [
                'attr' => ['class' => 'form-control-sm'],
                'required' => false,
            ])
            ->add('customer', CustomerAutocompleteField::class, [
                'attr' => ['class' => 'form-control-sm'],
                'required' => false,
            ])
            ->setMethod('GET')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => OrderFilters::class,
        ]);
    }
}
