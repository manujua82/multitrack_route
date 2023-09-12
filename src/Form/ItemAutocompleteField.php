<?php

namespace App\Form;

use App\Entity\Item;
use App\Repository\ItemRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\UX\Autocomplete\Form\AsEntityAutocompleteField;
use Symfony\UX\Autocomplete\Form\ParentEntityAutocompleteType;
use Symfony\Bundle\SecurityBundle\Security;

#[AsEntityAutocompleteField]
class ItemAutocompleteField extends AbstractType
{
    private $mainCompany;

    public function __construct(Security $security)
    {
        $this->mainCompany = $security->getUser()->getMainCompany();
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'class' => Item::class,
            'placeholder' => 'Choose a Driver',
            'query_builder' => function (ItemRepository $itemRepository) {
                return $itemRepository->createQueryBuilder('item')
                    ->andWhere('item.company = :company')
                    ->setParameter('company', $this->mainCompany);
            },
        ]);
    }

    public function getParent(): string
    {
        return ParentEntityAutocompleteType::class;
    }
}
