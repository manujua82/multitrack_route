<?php

namespace App\Repository;

use App\Entity\MainCompany;
use Symfony\Bundle\SecurityBundle\Security;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<MainCompany>
 *
 * @method MainCompany|null find($id, $lockMode = null, $lockVersion = null)
 * @method MainCompany|null findOneBy(array $criteria, array $orderBy = null)
 * @method MainCompany[]    findAll()
 * @method MainCompany[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MainCompanyRepository extends ServiceEntityRepository
{
    private $mainCompany;

    public function __construct(
        ManagerRegistry $registry,
        Security $security
    ) {
        parent::__construct($registry, MainCompany::class);
        $this->mainCompany = $security->getUser()->getMainCompany();
    }

    public function add(MainCompany $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
    /**
     * @return MainCompany
     */
    public function config()
    {
        return $this->mainCompany;
    }

    //    /**
    //     * @return MainCompany[] Returns an array of MainCompany objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('m')
    //            ->andWhere('m.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('m.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?MainCompany
    //    {
    //        return $this->createQueryBuilder('m')
    //            ->andWhere('m.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
