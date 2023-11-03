<?php

namespace App\Repository;

use App\Entity\Order;
use App\Entity\Route;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\SecurityBundle\Security;


/**
 * @extends ServiceEntityRepository<Order>
 *
 * @method Order|null find($id, $lockMode = null, $lockVersion = null)
 * @method Order|null findOneBy(array $criteria, array $orderBy = null)
 * @method Order[]    findAll()
 * @method Order[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrderRepository extends ServiceEntityRepository
{
    private $mainCompany;

    public function __construct(
        ManagerRegistry $registry,
        Security $security
    )
    {
        parent::__construct($registry, Order::class);
        $this->mainCompany = $security->getUser()->getMainCompany();
    }

    public function add(Order $entity, bool $flush = false): void
    {
        $entity->setCompany($this->mainCompany);
        $this->getEntityManager()->persist($entity);
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function delete(Order $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    private function getBaseOrdersList(): QueryBuilder
    {
        return $this->createQueryBuilder('o')
            ->leftJoin('o.shipFrom', 'w')
            ->addSelect('w')
            ->leftJoin('o.shipper', 's')
            ->addSelect('s')
            ->leftJoin('o.customerId', 'c')
            ->addSelect('c')
            ->leftJoin('o.addressId', 'a')
            ->addSelect('a')
            ->andWhere('o.company = :company')
            ->setParameter('company', $this->mainCompany)
            ->orderBy('o.created', 'DESC');
    }

    public function findAllByCompany(): array
    {
        $baseQuery = $this->getBaseOrdersList();
        return $baseQuery->getQuery()->getResult();
    }

    public function searchByFilters(): QueryBuilder
    {
        $baseQuery = $this->getBaseOrdersList();
        // TODO: apply Filters
        return $baseQuery;
    }

    public function getOrdersByStatus(string $status): array
    {
        $baseQuery =  $this->getBaseOrdersList();
        $baseQuery->andWhere('o.status = :status')
                  ->setParameter('status', $status);
        
        return $baseQuery->getQuery()->getResult();
    }

    public function getOrderByRoute(Route $route): array
    {
        $baseQuery = $this->getBaseOrdersList();
        $baseQuery->andWhere('o.route = :route')
                  ->setParameter('route', $route);
        return $baseQuery->getQuery()->getResult();
    }
}
