<?php

namespace App\DataFixtures;

use Faker;
use App\Entity\Genre;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class GenreFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');

        // ? Création des genres
        $value = ['Fiction', 'Thriller', 'Horreur', 'Biographie', 'Roman', 'Theatre'];
        for ($i = 1; $i <= count($value); $i++) {
        $genres = [];
        $genre = new Genre();
        $genre->setNom($value[$i -1]);

        $manager->persist($genre);
        $genres[] = $genre;

        // ? Envoyer en base de donnée
        $manager->flush();
        }
    }
}
