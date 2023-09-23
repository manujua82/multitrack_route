<?php

namespace App\Repository;

use App\Entity\Route;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\SecurityBundle\Security;

/**
 * @extends ServiceEntityRepository<Route>
 *
 * @method Route|null find($id, $lockMode = null, $lockVersion = null)
 * @method Route|null findOneBy(array $criteria, array $orderBy = null)
 * @method Route[]    findAll()
 * @method Route[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RouteRepository extends ServiceEntityRepository
{
    private $mainCompany;

    public function __construct(
        ManagerRegistry $registry,
        Security $security
    )
    {
        parent::__construct($registry, Route::class);
        $this->mainCompany = $security->getUser()->getMainCompany();
    }


    public function add(Route $entity, bool $flush = false): void
    {
        $entity->setCompany($this->mainCompany);
        $this->getEntityManager()->persist($entity);
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findByDateRange(DateTime $from, DateTime $till): array
    {
        $qb = $this->createQueryBuilder('r')
        ->leftJoin('r.driver', 'd')
        ->addSelect('d')
        ->leftJoin('r.shipFrom', 'w')
        ->addSelect('w')
        ->andWhere('r.company = :company')
        ->setParameter('company', $this->mainCompany)
        ->addOrderBy('r.date', 'DESC') 
        ->addOrderBy('r.time', 'ASC')
        ->addOrderBy('r.number', 'DESC');

        if ($from && $till) {
            $qb->andWhere('r.date BETWEEN :dateFrom AND :dateTill')
                ->setParameter('dateFrom', $from->format('Y-m-d'))
                ->setParameter('dateTill', $till->format('Y-m-d'));
        }

        return $qb->getQuery()->getResult();
    }

    public function delete(Route $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
//    /**
//     * @return Route[] Returns an array of Route objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('r.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Route
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
