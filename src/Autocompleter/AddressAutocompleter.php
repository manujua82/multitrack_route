<?php

namespace App\Autocompleter;
use Symfony\Component\Security\Core\Security;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use App\Entity\Address;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;
use Symfony\UX\Autocomplete\EntityAutocompleterInterface;

#[AutoconfigureTag('ux.entity_autocompleter', ['alias' => 'Address'])]
class AddressAutocompleter implements EntityAutocompleterInterface
{
    public function getEntityClass(): string
    {
        return Address::class;
    }

    public function createFilteredQueryBuilder(EntityRepository $repository, string $query): QueryBuilder
    {
        return $repository
            // the alias "food" can be anything
            ->createQueryBuilder('address')
            ->andWhere('address.code LIKE :search or address.street LIKE :search ')
            ->setParameter('search', '%'.$query.'%')

            // maybe do some custom filtering in all cases
            //->andWhere('food.isHealthy = :isHealthy')
            //->setParameter('isHealthy', true)
        ;
    }

    public function getLabel(object $entity): string
    {
        return $entity->getStreet();
    }

    public function getValue(object $entity): string
    {
        return $entity->getId();
    }

    public function isGranted(Security $security): bool
    {
        // see the "security" option for details
        return true;
    }

        /* public function getGroupBy(): mixed; */
}
