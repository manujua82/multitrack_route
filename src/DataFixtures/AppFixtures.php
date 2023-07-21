<?php

namespace App\DataFixtures;

use App\Entity\MainCompany;
use DateTime;
use App\Entity\User;
use App\Entity\MicroPost;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(
        private UserPasswordHasherInterface $userPasswordHasher
    ) {
    }

    public function load(ObjectManager $manager): void
    {

        $testCompany = new MainCompany();
        $testCompany->setName('test');
        $testCompany->setCreatedDate(new DateTime());
        $manager->persist($testCompany);

        $user1 = new User();
        $user1->setEmail('test@test.com');
        $user1->setPassword(
            $this->userPasswordHasher->hashPassword(
                $user1,
                '12345678'
            )
        );
        $user1->setMainCompany($testCompany);
        $manager->persist($user1);

        $manager->flush();

        // $user2 = new User();
        // $user2->setEmail('juan@test.com');
        // $user2->setPassword(
        //     $this->userPasswordHasher->hashPassword(
        //         $user2,
        //         '12345678'
        //     )
        // );
        // $manager->persist($user2);

        // $microPost1 = new MicroPost();
        // $microPost1->setTitle("Welcome to Poland");
        // $microPost1->setText("Welcome to Poland!");
        // $microPost1->setCreated(new DateTime());
        // $manager->persist($microPost1);

        // $microPost2 = new MicroPost();
        // $microPost2->setTitle("Welcome to US");
        // $microPost2->setText("Welcome to US!");
        // $microPost2->setCreated(new DateTime());
        // $manager->persist($microPost2);

        // $microPost3 = new MicroPost();
        // $microPost3->setTitle("Welcome to Venezuela");
        // $microPost3->setText("Welcome to US!");
        // $microPost3->setCreated(new DateTime());
        // $manager->persist($microPost3);
        
        // $manager->flush();
    }
}
