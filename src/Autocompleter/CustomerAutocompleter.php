<?php

namespace App\Autocompleter;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use App\Entity\Customer;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;
use Symfony\UX\Autocomplete\EntityAutocompleterInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Security as SecurityCore;



#[AutoconfigureTag('ux.entity_autocompleter', ['alias' => 'Customer'])]
class CustomerAutocompleter implements EntityAutocompleterInterface
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
        return Customer::class;
    }

    public function createFilteredQueryBuilder(EntityRepository $repository, string $query): QueryBuilder
    {
        return $repository
            ->createQueryBuilder('customer')
            ->andWhere('customer.code LIKE :search or customer.name LIKE :search')
            ->andWhere('customer.company = :company')
            ->setParameter('search', '%'.$query.'%')
            ->setParameter('company', $this->mainCompany)


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

    public function isGranted(SecurityCore $security): bool
    {
        // see the "security" option for details
        return true;
    }
}
