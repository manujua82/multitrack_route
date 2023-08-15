<?php

namespace App\Repository;

use App\Entity\Shipper;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\SecurityBundle\Security;

/**
 * @extends ServiceEntityRepository<Shipper>
 *
 * @method Shipper|null find($id, $lockMode = null, $lockVersion = null)
 * @method Shipper|null findOneBy(array $criteria, array $orderBy = null)
 * @method Shipper[]    findAll()
 * @method Shipper[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ShipperRepository extends ServiceEntityRepository
{
    private $mainCompany;

    public function __construct(
        ManagerRegistry $registry,
        Security $security
    )
    {
        parent::__construct($registry, Shipper::class);
        $this->mainCompany = $security->getUser()->getMainCompany();
    }

    public function add(Shipper $entity, bool $flush = false): void
    {
        $entity->setCompany($this->mainCompany);
        $this->getEntityManager()->persist($entity);
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findAllByCompany(): array
    {
        return $this->createQueryBuilder('shipper')
            ->andWhere('shipper.company = :company')
            ->setParameter('company', $this->mainCompany)
            ->orderBy('shipper.created', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function delete(Shipper $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return Shipper[] Returns an array of Shipper objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('s.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Shipper
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
