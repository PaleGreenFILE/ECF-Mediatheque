<?php

namespace App\DataFixtures;

use Faker;
use App\Entity\Livre;
use DateTimeImmutable;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class LivreFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');

        // ? Le compte Livres
        for ($i = 1; $i <= 50 ; $i++) { 
            $livres = [];
            $livre = new Livre();
            $livre->setTitre($faker->word(4));
            $livre->setIllustration('https://picsum.photos/360/360');
            $livre->setParution($faker->dateTimeThisCentury());
            $livre->setDescription($faker->paragraph(2, false));
            $livre->setAuteur($faker->firstname() . ' ' . $faker->lastname());

            $manager->persist($livre);
            $livres[] = $livre;
        }

        // ? Envoyer en base de donnée
        $manager->flush();
    }
}
