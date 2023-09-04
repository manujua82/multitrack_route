<?php

namespace App\Autocompleter;

use App\Entity\Item;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Security as SecurityCore;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;
use Symfony\UX\Autocomplete\EntityAutocompleterInterface;

#[AutoconfigureTag('ux.entity_autocompleter', ['alias' => 'Items'])]
class ItemsAutocompleter implements EntityAutocompleterInterface
{
    private $mainCompany;

    public function __construct(Security $security)
    {
        $this->mainCompany = $security->getUser()->getMainCompany();
    }
    public function getEntityClass(): string
    {
        return Item::class;
    }

    public function createFilteredQueryBuilder(EntityRepository $repository, string $query): QueryBuilder
    {
        return $repository
            // the alias "food" can be anything
            ->createQueryBuilder('item')
            ->andWhere('item.code LIKE :search or item.name LIKE :search ')
            ->andWhere('item.company = :company')
            ->setParameter('search', '%'.$query.'%')
            ->setParameter('company', $this->mainCompany)
        ;
    }

    public function getLabel(object $entity): string
    {
        return $entity->getName();
    }

    public function getValue(object $entity): string
    {
        return $entity->getId();
    }

    public function isGranted(SecurityCore $security): bool
    {
        // see the "security" option for details
        return true;
    }
}

