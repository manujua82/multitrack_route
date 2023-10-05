<?php

namespace App\Repository;

use App\Entity\RouteAddress;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<RouteAddress>
 *
 * @method RouteAddress|null find($id, $lockMode = null, $lockVersion = null)
 * @method RouteAddress|null findOneBy(array $criteria, array $orderBy = null)
 * @method RouteAddress[]    findAll()
 * @method RouteAddress[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RouteAddressRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RouteAddress::class);
    }

//    /**
//     * @return RouteAddress[] Returns an array of RouteAddress objects
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

//    public function findOneBySomeField($value): ?RouteAddress
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
