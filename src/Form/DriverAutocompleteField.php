<?php

namespace App\Form;

use App\Entity\Driver;
use App\Repository\DriverRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\UX\Autocomplete\Form\AsEntityAutocompleteField;
use Symfony\UX\Autocomplete\Form\ParentEntityAutocompleteType;
use Symfony\Bundle\SecurityBundle\Security;

#[AsEntityAutocompleteField]
class DriverAutocompleteField extends AbstractType
{
    private $mainCompany;

    public function __construct(Security $security)
    {
        $this->mainCompany = $security->getUser()->getMainCompany();
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'class' => Driver::class,
            'placeholder' => 'Choose a Driver',
            // 'choice_label' => 'name',

            'query_builder' => function (DriverRepository $driverRepository) {
                return $driverRepository->createQueryBuilder('driver')
                    ->andWhere('driver.company = :company')
                    ->setParameter('company', $this->mainCompany);
            },
        ]);
    }

    public function getParent(): string
    {
        return ParentEntityAutocompleteType::class;
    }
}
