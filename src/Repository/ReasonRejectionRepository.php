<?php

namespace App\Repository;

use App\Entity\ReasonRejection;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\SecurityBundle\Security;

/**
 * @extends ServiceEntityRepository<ReasonRejection>
 *
 * @method ReasonRejection|null find($id, $lockMode = null, $lockVersion = null)
 * @method ReasonRejection|null findOneBy(array $criteria, array $orderBy = null)
 * @method ReasonRejection[]    findAll()
 * @method ReasonRejection[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReasonRejectionRepository extends ServiceEntityRepository
{
    private $mainCompany;

    public function __construct(
        ManagerRegistry $registry,
        Security $security
    )
    {
        parent::__construct($registry, ReasonRejection::class);
        $this->mainCompany = $security->getUser()->getMainCompany();
    }

    public function add(ReasonRejection $entity, bool $flush = false): void
    {
        $entity->setCompany($this->mainCompany);
        $this->getEntityManager()->persist($entity);
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function delete(ReasonRejection $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findAllByReasonType($type = 'SITE'): array
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.company = :company')
            ->andWhere('r.type = :type')
            ->setParameter('company', $this->mainCompany)
            ->setParameter('type', $type)
            ->orderBy('r.created', 'DESC')
            ->getQuery()
            ->getResult();
    }

//    /**
//     * @return ReasonRejection[] Returns an array of ReasonRejection objects
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

//    public function findOneBySomeField($value): ?ReasonRejection
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
