<?php

namespace App\DataFixtures;

use Faker;
use App\Entity\User;
use DateTimeImmutable;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager)
    {
        
        $faker = Faker\Factory::create('fr_FR');

        // ? Le compte Admin
        $users = [];
        $user = new User();
        $user->setNom('Briffard');
        $user->setPrenom('Pascal');
        $user->setDateNaissance(new \DateTime('1985-02-20'));
        $user->setAdresse('15 rue de la Liberté 59600 Maubeuge');
        $user->setEmail('admin@email.fr');
        $hash = $this->passwordHasher->hashPassword($user, ('password'));
        $user->setPassword($hash);
        $user->setRoles(['ROLE_ADMIN']);
        $user->setEmpruntMax(999);
        $user->setIsAutorise(1);
        $user->setIsVerified(1);

        $manager->persist($user);
        $users[] = $user;

        // ? Le compte Libraire
        $users = [];
        $user = new User();
        $user->setNom('Lavisse');
        $user->setPrenom('Jean-Baptiste');
        $user->setDateNaissance(new \DateTime('1980-11-13'));
        $user->setAdresse('136 bis Faubourg Saint Honoré 59000 Lille');
        $user->setEmail('libraire@email.fr');
        $hash = $this->passwordHasher->hashPassword($user, ('password'));
        $user->setPassword($hash);
        $user->setRoles(['ROLE_LIBRAIRE']);
        $user->setEmpruntMax(999);
        $user->setIsAutorise(1);
        $user->setIsVerified(1);

        $manager->persist($user);
        $users[] = $user;

        // ? Création de 20 comptes utilisateurs
        for ($i = 1; $i <= 20; $i++) { 
            $users = [];
            $user = new User();
            $user->setNom($faker->lastname());
            $user->setPrenom($faker->firstname());
            $user->setDateNaissance($faker->dateTimeThisCentury());
            $user->setAdresse($faker->address());
            $user->setEmail('user' .$i. '@email.fr');
            $user->setPassword($this->passwordHasher->hashPassword($user, 'password'));
            $user->setRoles(['ROLE_USER']);
            $user->setIsAutorise($faker->boolean());
            $user->setIsVerified(0);
            $user->setEmpruntMax(10);

            $manager->persist($user);
            $users[] = $user;
        }

        // ? Envoyer en base de donnée
        $manager->flush();
    }

    // php bin/console doctrine:fixtures:load
}
