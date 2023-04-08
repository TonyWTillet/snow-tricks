<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class CategoryFixtures extends Fixture
{
    public const CATEGORY_DEBUTANT = 'debutant';
    public const CATEGORY_INTERMEDIAIRE = 'intermediaire';
    public const CATEGORY_EXPERIMENTE = 'experimente';
    public function load(ObjectManager $manager)
    {

        $categoryFixtures = [
            0 => [
                'name' => 'Débutant',
            ],
            1 => [
                'name' => 'Intermédiaire',
            ],
            2 => [
                'name' => 'Avancé',
            ],
        ];



        for ($c = 0; $c < 3; $c++) {
            $category = new Category();
            $category->setName($categoryFixtures[$c]['name']);
            if ($c <1) {
                $this->addReference(self::CATEGORY_DEBUTANT, $category);
            } elseif ($c < 2) {
                $this->addReference(self::CATEGORY_INTERMEDIAIRE, $category);
            } else {
                $this->addReference(self::CATEGORY_EXPERIMENTE, $category);
            }
            $manager->persist($category);
        }
        $manager->flush();
    }
}