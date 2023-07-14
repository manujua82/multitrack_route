<?php

namespace App\DataFixtures;

use DateTime;
use App\Entity\MicroPost;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $microPost1 = new MicroPost();
        $microPost1->setTitle("Welcome to Poland");
        $microPost1->setText("Welcome to Poland!");
        $microPost1->setCreated(new DateTime());
        $manager->persist($microPost1);

        $microPost2 = new MicroPost();
        $microPost2->setTitle("Welcome to US");
        $microPost2->setText("Welcome to US!");
        $microPost2->setCreated(new DateTime());
        $manager->persist($microPost2);

        $microPost3 = new MicroPost();
        $microPost3->setTitle("Welcome to Venezuela");
        $microPost3->setText("Welcome to US!");
        $microPost3->setCreated(new DateTime());
        $manager->persist($microPost3);
        
        $manager->flush();
    }
}
