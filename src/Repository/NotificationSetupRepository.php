<?php

namespace App\Repository;

use App\Entity\NotificationSetup;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<NotificationSetup>
 *
 * @method NotificationSetup|null find($id, $lockMode = null, $lockVersion = null)
 * @method NotificationSetup|null findOneBy(array $criteria, array $orderBy = null)
 * @method NotificationSetup[]    findAll()
 * @method NotificationSetup[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NotificationSetupRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, NotificationSetup::class);
    }

//    /**
//     * @return NotificationSetup[] Returns an array of NotificationSetup objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('n')
//            ->andWhere('n.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('n.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?NotificationSetup
//    {
//        return $this->createQueryBuilder('n')
//            ->andWhere('n.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
