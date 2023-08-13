<?php

namespace App\Form;

use App\Entity\Warehouse;
use App\Repository\WarehouseRepository;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\UX\Autocomplete\Form\AsEntityAutocompleteField;
use Symfony\UX\Autocomplete\Form\ParentEntityAutocompleteType;

#[AsEntityAutocompleteField]
class WarehouseAutocompleteField extends AbstractType
{
    private $mainCompany;

    public function __construct(Security $security)
    {
        $this->mainCompany = $security->getUser()->getMainCompany();
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'class' => Warehouse::class,
            'placeholder' => 'Choose a Warehouse',
            // 'choice_label' => 'name',

            'query_builder' => function (WarehouseRepository $warehouseRepository) {
                return $warehouseRepository->createQueryBuilder('warehouse')
                ->andWhere('warehouse.company = :company')
                ->setParameter('company', $this->mainCompany);
            },
            // 'security' => 'ROLE_SOMETHING',
        ]);
    }

    public function getParent(): string
    {
        return ParentEntityAutocompleteType::class;
    }
}
