<?php

namespace App\Form;

use App\Entity\Carrier;
use App\Repository\CarrierRepository;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\UX\Autocomplete\Form\AsEntityAutocompleteField;
use Symfony\UX\Autocomplete\Form\ParentEntityAutocompleteType;

#[AsEntityAutocompleteField]
class CarrierAutocompleteField extends AbstractType
{
    private $mainCompany;

    public function __construct(Security $security)
    {
        $this->mainCompany = $security->getUser()->getMainCompany();
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'class' => Carrier::class,
            'placeholder' => 'Choose a Carrier',
            // 'choice_label' => 'name',

            'query_builder' => function (CarrierRepository $carrierRepository) {
                return $carrierRepository->createQueryBuilder('carrier')
                ->andWhere('carrier.company = :company')
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
