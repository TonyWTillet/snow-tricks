<?php

namespace App\DataFixtures;

use App\Entity\Picture;
use App\Entity\Trick;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class PictureFixtures extends Fixture implements DependentFixtureInterface
{
    public const DEFAULT_IMAGE = 'default-image';
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        for ($i = 0; $i < 10; $i++) {
            $picture = new Picture();
            $picture->setName($faker->word);
            $picture->setAlt($faker->word);
            $picture->setTrick($this->getReference(AppFixtures::TRICK_0));
            $picture->setCreatedAt($faker->dateTimeBetween('-6 months'));
            $picture->setUpdatedAt($faker->dateTimeBetween('-3 months'));
            $manager->persist($picture);
        }
        $this->setReference(self::DEFAULT_IMAGE, $picture);
        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            AppFixtures::class,
        ];
    }
}