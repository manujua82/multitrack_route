<?php

namespace App\Autocompleter;
use Symfony\Component\Security\Core\Security;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use App\Entity\Customer;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;
use Symfony\UX\Autocomplete\EntityAutocompleterInterface;

#[AutoconfigureTag('ux.entity_autocompleter', ['alias' => 'Customer'])]
class CustomerAutocompleter implements EntityAutocompleterInterface
{
    public function getEntityClass(): string
    {
        return Customer::class;
    }

    public function createFilteredQueryBuilder(EntityRepository $repository, string $query): QueryBuilder
    {
        return $repository
            ->createQueryBuilder('customer')
            ->andWhere('customer.code LIKE :search or customer.name LIKE :search')
            ->setParameter('search', '%'.$query.'%')

            // maybe do some custom filtering in all cases
            //->andWhere('food.isHealthy = :isHealthy')
            //->setParameter('isHealthy', true)
        ;
    }

    public function getLabel(object $entity): string
    {
        return $entity->getCode();
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
}
