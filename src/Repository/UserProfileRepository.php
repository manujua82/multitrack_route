<?php

namespace App\Repository;

use App\Entity\User;
use App\Entity\UserProfile;
use App\Entity\MainCompany;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<UserProfile>
 *
 * @method UserProfile|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserProfile|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserProfile[]    findAll()
 * @method UserProfile[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserProfileRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserProfile::class);
    }
    
    public function create(string $name, string $rolegroup, User $user)
    {
        $newUser = new UserProfile();
        $newUser->setName($name);
        $newUser->setRolegroup($rolegroup);
        $newUser->setUser($user);
        $this->getEntityManager()->persist($newUser);
        $this->getEntityManager()->flush();

        return $newUser;
    }
    
    public function update(UserProfile $entity, string $name, string $rolegroup)
    {
        $entity->setName($name);
        $entity->setRolegroup($rolegroup);
        $this->getEntityManager()->persist($entity);
        $this->getEntityManager()->flush();

        return $entity;
    }

//    /**
//     * @return UserProfile[] Returns an array of UserProfile objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('u.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?UserProfile
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
