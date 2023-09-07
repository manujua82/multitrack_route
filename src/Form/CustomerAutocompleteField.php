<?php

namespace App\Form;

use App\Entity\Customer;
use App\Repository\CustomerRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\UX\Autocomplete\Form\AsEntityAutocompleteField;
use Symfony\UX\Autocomplete\Form\ParentEntityAutocompleteType;
use Symfony\Bundle\SecurityBundle\Security;

#[AsEntityAutocompleteField]
class CustomerAutocompleteField extends AbstractType
{
    private $mainCompany;

    public function __construct(Security $security)
    {
        $this->mainCompany = $security->getUser()->getMainCompany();
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'class' => Customer::class,
            'placeholder' => 'Choose a Customer',
            'query_builder' => function (CustomerRepository $repository) {
                return $repository->createQueryBuilder('customer')
                    ->andWhere('customer.company = :company')
                    ->setParameter('company', $this->mainCompany);
            },
        ]);
    }

    public function getParent(): string
    {
        return ParentEntityAutocompleteType::class;
    }
}
