<?php

namespace App\Repository;

use App\Entity\Correlatives;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\SecurityBundle\Security;


/**
 * @extends ServiceEntityRepository<Correlatives>
 *
 * @method Correlatives|null find($id, $lockMode = null, $lockVersion = null)
 * @method Correlatives|null findOneBy(array $criteria, array $orderBy = null)
 * @method Correlatives[]    findAll()
 * @method Correlatives[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CorrelativesRepository extends ServiceEntityRepository
{
    private $mainCompany;

    public function __construct(
        ManagerRegistry $registry,
        Security $security
    )
    {
        parent::__construct($registry, Correlatives::class);
        $this->mainCompany = $security->getUser()->getMainCompany();
    }

    public function getByDocumentType(string $documentType): ?Correlatives
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.documentType = :documentType')
            ->andWhere('c.company = :company')
            ->setParameter('company', $this->mainCompany)
            ->setParameter('documentType', $documentType)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function update(Correlatives $correlative, bool $flush = false): void
    {
        $correlative->updateLastUsed();
        $this->getEntityManager()->persist($correlative);
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return Correlatives[] Returns an array of Correlatives objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Correlatives
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
