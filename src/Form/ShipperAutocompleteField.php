<?php

namespace App\Form;

use App\Entity\Shipper;
use App\Repository\ShipperRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\UX\Autocomplete\Form\AsEntityAutocompleteField;
use Symfony\UX\Autocomplete\Form\ParentEntityAutocompleteType;
use Symfony\Bundle\SecurityBundle\Security;

#[AsEntityAutocompleteField]
class ShipperAutocompleteField extends AbstractType
{
    private $mainCompany;

    public function __construct(Security $security)
    {
        $this->mainCompany = $security->getUser()->getMainCompany();
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'class' => Shipper::class,
            'placeholder' => 'Choose a Shipper',
            'query_builder' => function (ShipperRepository $repository) {
                return $repository->createQueryBuilder('shipper')
                    ->andWhere('shipper.company = :company')
                    ->setParameter('company', $this->mainCompany);
            },
        ]);
    }

    public function getParent(): string
    {
        return ParentEntityAutocompleteType::class;
    }
}
