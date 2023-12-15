<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class UserFixtures extends Fixture
{
    public const ADMIN_USER_REFERENCE = 'admin';
    const USER_REFERENCE = 'user';

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');

        $userFixtures = [
            0 => [
                'pseudo' => 'admin',
                'email' => 'tony.tillet@gmail.com',
                'password' => password_hash('admin', PASSWORD_DEFAULT),
                'role' => User::USER_PERMISSIONS[1],
            ],
            1 => [
                'pseudo' => 'user',
                'email' => 'user@gmail.com',
                'password' => password_hash('user', PASSWORD_DEFAULT),
                'role' => User::USER_PERMISSIONS[0],
            ]
        ];

        for ($u = 0; $u < 2; $u++) {
            $user = new User();
            $user->setPseudo($userFixtures[$u]['pseudo']);
            $user->setEmail($userFixtures[$u]['email']);
            $user->setPassword($userFixtures[$u]['password']);
            $user->setRole($userFixtures[$u]['role']);
            $user->setIsVerified(1);
            $user->setCreatedAt($faker->dateTimeBetween('-6 months'));
            $user->setUpdatedAt($faker->dateTimeBetween('-6 months'));
            if ($u < 1) {
                $this->addReference(self::ADMIN_USER_REFERENCE, $user);
            } else {
                $this->addReference(self::USER_REFERENCE, $user);
            }
            $manager->persist($user);
        }


        $manager->flush();
    }
}
