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

        $images = [
            0 => [
                'name' => 'rotation_cork_1.jpg',
                'alt' => 'Rotation Cork',
                ],
            1 => [
                'name' => 'frontside_360_2.jpg',
                'alt' => 'Frontside 360',
                ],
            2 => [
                'name' => 'indy_grab_3.jpg',
                'alt' => 'Indy grab',
                ],
            3 => [
                'name' => 'boardslide_4.jpg',
                'alt' => 'Boardslide',
                ],
            4 => [
                'name' => 'nose_grab_5.jpg',
                'alt' => 'Nose grab',
                ],
            5 => [
                'name' => 'nollie_6.jpg',
                'alt' => 'Nollie Ollie',
                ],
            6 => [
                'name' => 'tail_press_7.jpg',
                'alt' => 'Tail press',
                ],
            7 => [
                'name' => 'switch_8.jpg',
                'alt' => 'Switch',
                ],
            8 => [
                'name' => 'butter_9.jpg',
                'alt' => 'Butter',
                ],
            9 => [
                'name' => 'backflip_10.jpg',
                'alt' => 'Backflip',
                ],
        ];

        $trickId = [
            0 => AppFixtures::TRICK_0,
            1 => AppFixtures::TRICK_1,
            2 => AppFixtures::TRICK_2,
            3 => AppFixtures::TRICK_3,
            4 => AppFixtures::TRICK_4,
            5 => AppFixtures::TRICK_5,
            6 => AppFixtures::TRICK_6,
            7 => AppFixtures::TRICK_7,
            8 => AppFixtures::TRICK_8,
            9 => AppFixtures::TRICK_9,
        ];

        for ($i = 0; $i < 10; $i++) {
            $picture = new Picture();
            $picture->setName($images[$i]['name']);
            $picture->setAlt($images[$i]['alt']);
            $picture->setTrick($this->getReference($trickId[$i]));
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