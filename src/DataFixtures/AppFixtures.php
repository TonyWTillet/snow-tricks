<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Trick;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {

        $faker = Factory::create('fr_FR');
        for ($i = 0; $i < 50; $i++) {
            $trick = new Trick();
            $user = new User();
            $category = new Category();


            $trick->setCategory($faker->randomElement(['Débutant', 'Amateur', 'Confirmé']));
            $trick->setUser($faker->randomElement(['1', '2', '3']));
            $trick->setName($faker->words(4, true));
            $trick->setContent($faker->realText(1800));
            $trick->setCreatedAt($faker->dateTimeBetween('-6 months'));
            $trick->setUpdatedAt($faker->dateTimeBetween('-3 months'));
            $manager->persist($trick);
        }

        $manager->flush();
    }
}
