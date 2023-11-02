<?php

namespace App\Repository;

use App\Entity\Customer;
use Symfony\Bundle\SecurityBundle\Security;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Customer>
 *
 * @method Customer|null find($id, $lockMode = null, $lockVersion = null)
 * @method Customer|null findOneBy(array $criteria, array $orderBy = null)
 * @method Customer[]    findAll()
 * @method Customer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CustomerRepository extends ServiceEntityRepository
{
    private $mainCompany;

    public function __construct(
        ManagerRegistry $registry,
        Security $security
    )
    {
        parent::__construct($registry, Customer::class);
        $this->mainCompany = $security->getUser()->getMainCompany();
    }

    public function add(Customer $entity, bool $flush = false): void
    {
        $entity->setCompany($this->mainCompany);        
        $this->getEntityManager()->persist($entity);
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function delete(Customer $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findAllByCompany(): array
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.company = :company')
            ->setParameter('company', $this->mainCompany)
            ->orderBy('c.created', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function searchByQuery(string $parent): QueryBuilder
    {
        return $this->createQueryBuilder('c')
            ->orWhere('c.code LIKE :parent')
            ->orWhere('c.name LIKE :nameParent')   
            ->andWhere('c.company = :company')
            ->setParameter('parent', $parent . '%')
            ->setParameter('nameParent',  '%' . $parent . '%')
            ->setParameter('company', $this->mainCompany)
            ->orderBy('c.created', 'DESC');
    }

//    /**
//     * @return Customer[] Returns an array of Customer objects
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

//    public function findOneBySomeField($value): ?Customer
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
