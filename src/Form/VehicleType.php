<?php

namespace App\Form;

use App\Entity\Vehicle;
use App\Repository\CarrierRepository;
use App\Repository\DriverRepository;
use App\Repository\WarehouseRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VehicleType extends AbstractType
{
    private $driverRepository;
    private $warehouseRepository;
    private $carrierRepository;
    // private $mainCompany;

    public function __construct(
        DriverRepository $driverRepository,
        WarehouseRepository $warehouseRepository,
        CarrierRepository $carrierRepository

    )
    {
        $this->driverRepository = $driverRepository;
        $this->warehouseRepository = $warehouseRepository;
        $this->carrierRepository = $carrierRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Number')
            ->add('weight')
            ->add('volume')
            ->add('plt')
            ->add('driver', DriverAutocompleteField::class)
            ->add('depot', WarehouseAutocompleteField:: class)
            ->add('carrier',CarrierAutocompleteField::class)
            ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Vehicle::class,
        ]);
    }
}
