<?php

namespace App\Form;

use App\Entity\RoutingSetup;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RoutingSetupType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('startTime', TimeType::class, [
                'input'  => 'datetime',
                'widget' => 'single_text', 
                'html5' => false,
            ])
            ->add('stopDuration')
            ->add('startFromDeport')
            ->add('endFromDepot')
            ->add('driverHomeLocation')
            ->add('costPerDistance')
            ->add('costPerHour')
            ->add('baseFare')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => RoutingSetup::class,
        ]);
    }
}
