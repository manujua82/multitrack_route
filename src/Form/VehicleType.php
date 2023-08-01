<?php

namespace App\Form;

use App\Entity\Driver;
use App\Entity\Vehicle;
use App\Entity\Warehouse;
use App\Entity\Carrier;
use App\Repository\CarrierRepository;
use App\Repository\DriverRepository;
use App\Repository\WarehouseRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\SecurityBundle\Security;
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
        Security $security,
        DriverRepository $driverRepository,
        WarehouseRepository $warehouseRepository,
        CarrierRepository $carrierRepository

    )
    {
        $this->driverRepository = $driverRepository;
        $this->warehouseRepository = $warehouseRepository;
        $this->carrierRepository = $carrierRepository;
        // $this->mainCompany = $security->getUser()->getMainCompany();
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Number')
            ->add('weight')
            ->add('volume')
            ->add('plt')
            ->add('driver', EntityType::class, [
                'class' => Driver::class,
                // 'choice_label' => function (Driver $driver) {
                //     return  sprintf('(%d) %s', $driver->getId(), $driver->getName());
                // },
                'placeholder'  => 'Choose a driver',
                'choices' => $this->driverRepository->findAllByCompany()
            ])
            ->add('depot',  EntityType::class, [
                'class' => Warehouse::class,
                'placeholder'  => 'Choose a depot',
                'choices' => $this->warehouseRepository->findAllByCompany()
            ])
            ->add('carrier', EntityType::class, [
                'class' => Carrier::class,
                'placeholder'  => 'Choose a carrier',
                'choices' => $this->carrierRepository->findAllByCompany()
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
