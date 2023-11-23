<?php

namespace App\DataFixtures;

use App\Entity\MicroPost;
use App\Entity\User;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(private UserPasswordHasherInterface $userPasswordHasher)
    {
        
    }
                

    public function load(ObjectManager $manager): void
    {
        $user1 = new User();
        $user1->setEmail('test@test.com');
        $user1->setPassword(
            $this->userPasswordHasher->hashPassword(
                $user1,
                '12345678'
            )
        );
        $manager->persist($user1);

        $user2 = new User();
        $user2->setEmail('john@test.com');
        $user2->setPassword(
            $this->userPasswordHasher->hashPassword(
                $user2,
                '12345678'
            )
        );
        $manager->persist($user2);
        
        $microPost = new MicroPost();
        $microPost->setTitle("Poland");
        $microPost->setText("Welcome to Poland!");
        $microPost->setCreation(new DateTime());
        $manager->persist($microPost);

        $microPost1 = new MicroPost();
        $microPost1->setTitle("Mada");
        $microPost1->setText("Welcome to Mada!");
        $microPost1->setCreation(new DateTime());
        $manager->persist($microPost1);

        $microPost2 = new MicroPost();
        $microPost2->setTitle("US");
        $microPost2->setText("Welcome to US!");
        $microPost2->setCreation(new DateTime());
        $manager->persist($microPost2);

        // $product = new Product();
        // $manager->persist($product);

        $manager->flush();
    }
}
