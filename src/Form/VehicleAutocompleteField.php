<?php

namespace App\Form;

use App\Entity\Vehicle;
use App\Repository\VehicleRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\UX\Autocomplete\Form\AsEntityAutocompleteField;
use Symfony\UX\Autocomplete\Form\ParentEntityAutocompleteType;
use Symfony\Bundle\SecurityBundle\Security;

#[AsEntityAutocompleteField]
class VehicleAutocompleteField extends AbstractType
{
    private $mainCompany;

    public function __construct(Security $security)
    {
        $this->mainCompany = $security->getUser()->getMainCompany();
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'class' => Vehicle::class,
            'placeholder' => 'Choose a vehicle',
            'query_builder' => function (VehicleRepository $repository) {
                return $repository->createQueryBuilder('v')
                    ->andWhere('v.company = :company')
                    ->setParameter('company', $this->mainCompany);
            },
        ]);
    }

    public function getParent(): string
    {
        return ParentEntityAutocompleteType::class;
    }
}
