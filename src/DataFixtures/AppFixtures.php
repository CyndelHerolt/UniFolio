<?php

namespace App\DataFixtures;

use App\Entity\Competence;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        for ($i = 0; $i < 10; $i++) {
            $competence = new Competence();

            $competence->setlibelle($faker->sentence())
                ->setNomCourt($faker->word())
                ->setCouleur($faker->hexColor)
                ->setCode($faker->numberBetween(100, 500))
                ->setUe($faker->word());


            $manager->persist($competence);
        }

        $manager->flush();
    }
}
