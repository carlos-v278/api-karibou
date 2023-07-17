<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Factory\ApiTokenFactory;
use App\Factory\BookFactory;
use App\Factory\UserFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);
        UserFactory::createMany(10);
    /*    BookFactory::createMany(40,function(){
            return [
                'user' => UserFactory::random()
            ];
        });*/
        ApiTokenFactory::createMany(30,function(){
            return [
                'ownedBy' => UserFactory::random()
            ];
        });
        $manager->flush();
    }
}
