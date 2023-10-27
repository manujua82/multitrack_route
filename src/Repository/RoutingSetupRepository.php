<?php

namespace App\Repository;

use App\Entity\RoutingSetup;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\QueryException;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\SecurityBundle\Security;

/**
 * @extends ServiceEntityRepository<RoutingSetup>
 *
 * @method RoutingSetup|null find($id, $lockMode = null, $lockVersion = null)
 * @method RoutingSetup|null findOneBy(array $criteria, array $orderBy = null)
 * @method RoutingSetup[]    findAll()
 * @method RoutingSetup[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RoutingSetupRepository extends ServiceEntityRepository
{
    private $mainCompany;

    public function __construct(
        ManagerRegistry $registry,
        Security $security
    )
    {
        parent::__construct($registry, RoutingSetup::class);
        $this->mainCompany = $security->getUser()->getMainCompany();
    }

    public function add(RoutingSetup $entity, bool $flush = false): void
    {
        $entity->setCompany($this->mainCompany);
        $this->getEntityManager()->persist($entity);
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function getRoutingSetup() {
        $qb = $this->createQueryBuilder('rs')
        ->andWhere('rs.company = :company')
        ->setParameter('company', $this->mainCompany);

        $response =  $qb->getQuery()->getResult();
        if ($response) {
            return $response[0];
        }
        return new RoutingSetup();
    }
}
