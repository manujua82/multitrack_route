<?php

namespace App\Repository;

use App\Entity\MainCompany;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * @extends ServiceEntityRepository<User>
 *
 * @implements PasswordUpgraderInterface<User>
 *
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    private UserPasswordHasherInterface $userPasswordHasher;

    public function __construct(
        ManagerRegistry $registry,
        UserPasswordHasherInterface $userPasswordHasher
    ) {
        $this->userPasswordHasher = $userPasswordHasher;
        parent::__construct($registry, User::class);
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', $user::class));
        }

        $user->setPassword(
            $this->userPasswordHasher->hashPassword(
                $user,
                $newPassword
            )
        );
        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();
    }

    public function createUser(string $email, string $password, MainCompany $company, array $roles)
    {
        $newUser = new User();
        $newUser->setEmail($email);
        $newUser->setPassword(
            $this->userPasswordHasher->hashPassword(
                $newUser,
                $password
            )
        );
        $newUser->setActive(true);
        $newUser->setDeleted(false);
        $newUser->setMainCompany($company);
        $newUser->setRoles($roles);
        $this->getEntityManager()->persist($newUser);
        $this->getEntityManager()->flush();

        return $newUser;
    }

    public function add(User $entity, bool $edit = false, bool $flush = true): void
    {
        $entity->setAgreedTerms();

        if (!$edit) {
            $entity->setPassword(
                $this->userPasswordHasher->hashPassword(
                    $entity,
                    'REGISTER_DEFAULT_USER'
                )
            );
        }
        $this->getEntityManager()->persist($entity);
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findAllByCompany(MainCompany $company): array
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.mainCompany = :company')
            ->andWhere('c.roleGroup IS NOT NULL')
            ->andWhere('c.deleted = 0')
            ->setParameter('company', $company)
            ->getQuery()
            ->getResult();
    }

    public function delete(User $entity, bool $flush = true): void
    {
        $entity->setDeleted(1);
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    //    /**
    //     * @return User[] Returns an array of User objects
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

    //    public function findOneBySomeField($value): ?User
    //    {
    //        return $this->createQueryBuilder('u')
    //            ->andWhere('u.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
