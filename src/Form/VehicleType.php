<?php

namespace App\Form;

use App\Entity\Driver;
use App\Entity\Vehicle;
use App\Entity\Warehouse;
use App\Entity\Carrier;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VehicleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Number')
            ->add('weight')
            ->add('volume')
            ->add('plt')
            ->add('driver', EntityType::class, [
                'class' => Driver::class,
                'choice_label' => function (Driver $driver) {
                    return  sprintf('(%d) %s', $driver->getId(), $driver->getName());
                },
                'placeholder'  => 'Choose a driver'
            ])
            ->add('depot',  EntityType::class, [
                'class' => Warehouse::class,
                'placeholder'  => 'Choose a depot'
            ])
            ->add('carrier', EntityType::class, [
                'class' => Carrier::class,
                'placeholder'  => 'Choose a carrier'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Vehicle::class,
        ]);
    }
}
