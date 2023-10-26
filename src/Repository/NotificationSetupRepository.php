<?php

namespace App\Repository;

use App\Entity\NotificationSetup;
use App\Entity\OrderStatus;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\SecurityBundle\Security;


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
    private $mainCompany;

    public function __construct(
        ManagerRegistry $registry,
        Security $security
    )
    {
        parent::__construct($registry, NotificationSetup::class);
        $this->mainCompany = $security->getUser()->getMainCompany();

    }

    public function add(NotificationSetup $entity, bool $flush = false): void
    {
        $entity->setCompany($this->mainCompany);
        $this->getEntityManager()->persist($entity);
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function getNotificationBy(string $documentType, string $documentStatus) {
        $qb = $this->createQueryBuilder('ns')
        ->andWhere('ns.company = :company')
        ->setParameter('company', $this->mainCompany)
        ->andWhere('ns.documentType = :documentType')
        ->setParameter('documentType', $documentType)
        ->andWhere('ns.documentStatus = :documentStatus')
        ->setParameter('documentStatus', $documentStatus);

        $response =  $qb->getQuery()->getResult();
        if ($response) {
            return $response[0];
        }
        return new NotificationSetup($documentType, $documentStatus);
    }
}
