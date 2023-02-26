<?php

namespace App\DataFixtures;

use App\Entity\Competence;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;

class CompetencesFixtures extends Fixture implements OrderedFixtureInterface
{
    public function getOrder()
    {
        // Retourner un entier qui indique l'ordre de chargement des fixations
        return 2;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        for ($i = 0; $i < 10; $i++) {

            $ues = array('Concevoir', 'Exprimer', 'Développer', 'Entreprendre', 'Comprendre');
            //retourne la value de la key random dans une variable $ue
            $ue = array_rand($ues, 1);

            $couleurs = array('#d07740'/*orange*/, '#e5b94d'/*yellow*/, '#416c3f'/*green*/, '#2b4c76'/*blue*/, '#9c2b26'/*red*/);
            $couleur = $couleurs[$ue];
            if ($ue === $ues[0]) {
                $couleur = $couleurs[0];
            } elseif ($ue === $ues[1]) {
                $couleur = $couleurs[1];
            } elseif ($ue === $ues[2]) {
                $couleur = $couleurs[2];
            } elseif ($ue === $ues[3]) {
                $couleur = $couleurs[3];
            } elseif ($ue === $ues[4]) {
                $couleur = $couleurs[4];
            }

            $competence = new Competence();

            $competence->setlibelle($faker->sentence())
                ->setNomCourt($faker->word())
                ->setCouleur($couleur)
                ->setCode($faker->numberBetween(100, 500))
                ->setUe($ues[$ue]);

            $manager->persist($competence);
        }

        $manager->flush();
    }
}
