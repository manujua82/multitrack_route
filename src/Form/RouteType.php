<?php

namespace App\Form;

use App\Entity\Route;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RouteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('number')
            ->add('date', DateType::class, [
                'mapped' => false,
                'widget' => 'single_text', 
            ])
            ->add('time', null, [
                'mapped' => false,
                'widget' => 'single_text', 
                'html5' => false,
            ])
            ->add('shipFrom', WarehouseAutocompleteField::class)
            ->add('vehicle', VehicleAutocompleteField::class)
            ->add('driver', DriverAutocompleteField::class)
            ->add('startFromDepot')
            ->add('endAtDepot')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Route::class,
        ]);
    }
}
