<?php

namespace App\Autocompleter;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Security as SecurityCore;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use App\Entity\Shipper;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;
use Symfony\UX\Autocomplete\EntityAutocompleterInterface;

#[AutoconfigureTag('ux.entity_autocompleter', ['alias' => 'Shipper'])]
class ShipperAutocompleter implements EntityAutocompleterInterface
{
    private $mainCompany;

    public function __construct(
        Security $security
    )
    {
        $this->mainCompany = $security->getUser()->getMainCompany();
    }


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
            ->andWhere('shipper.company = :company')
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

        /* public function getGroupBy(): mixed; */
}
