<?php

namespace App\DataFixtures;

use App\Entity\Champion;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ChampionFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {

        $championsList = ["Humain", "Elfe", "Nain", "Orc", "Loup-garou", "Vampire", "Ange", "Démon", "Dragon", "Fée", "Succube", "Golem", "Hybride", "Phoenix", "Géant", "Mort-vivant", "Sirène", "Harpie", "Minotaure", "Centaur", "Gorgone", "Chimère", "Liche", "Faune", "Gobelin", "Kitsune", "Homme-lézard", "Felin", "Fée des glaces", "Diablesse", "Sylphe", "Arachnide", "Elf noir", "Elementaliste", "Esprit", "Draeneï", "Troll", "Méduse", "Sirène", "Néréide", "Nymph", "Fenrir", "Gryphon", "Wendigo", "Kraken", "Banshee", "Centaure", "Dryade"];


        // Boucle à travers chaque champion
        foreach ($championsList as $championName) {
            $champion = new Champion();
            $champion
                ->setName($championName)
                ->setPv(mt_rand(250, 800))
                ->setPower(mt_rand(10, 60));

            $manager->persist($champion);
        }

        $manager->flush();
    }
}
