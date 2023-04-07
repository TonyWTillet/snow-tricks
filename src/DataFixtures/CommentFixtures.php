<?php

namespace App\DataFixtures;

use App\Entity\Comment;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class CommentFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');

        for ($i = 0; $i < 10; $i++) {
            $comment = new Comment();
            $comment->setTrick($this->getReference(AppFixtures::TRICK_0));
            if ($i % 2 === 0) {
                $comment->setUser($this->getReference(UserFixtures::USER_REFERENCE));
            } else {
                $comment->setUser($this->getReference(UserFixtures::ADMIN_USER_REFERENCE));
            }
            $comment->setContent($faker->text(500));
            $comment->setCreatedAt($faker->dateTimeBetween('-2 months'));
            $manager->persist($comment);
        }

       $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            AppFixtures::class,
            UserFixtures::class
        ];
    }
}