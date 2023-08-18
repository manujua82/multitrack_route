<?php

namespace App\Autocompleter;
use Symfony\Component\Security\Core\Security;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use App\Entity\Shipper;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;
use Symfony\UX\Autocomplete\EntityAutocompleterInterface;

#[AutoconfigureTag('ux.entity_autocompleter', ['alias' => 'Shipper'])]
class ShipperAutocompleter implements EntityAutocompleterInterface
{
    public function getEntityClass(): string
    {
        return Shipper::class;
    }

    public function createFilteredQueryBuilder(EntityRepository $repository, string $query): QueryBuilder
    {
        return $repository
            // the alias "food" can be anything
            ->createQueryBuilder('shipper')
            ->andWhere('shipper.code LIKE :search or shipper.name LIKE :search')
            ->setParameter('search', '%'.$query.'%')

            // maybe do some custom filtering in all cases
            //->andWhere('food.isHealthy = :isHealthy')
            //->setParameter('isHealthy', true)
        ;
    }

    public function getLabel(object $entity): string
    {
        return $entity->getCode() . ': '. $entity->getName();
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
