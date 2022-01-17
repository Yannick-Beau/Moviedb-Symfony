<?php

namespace App\DataFixtures;

use DateTime;
use Faker;
use App\Entity\Genre;
use App\Entity\Movie;
use App\Entity\Person;
use DateTimeImmutable;
use App\Entity\Casting;
use Doctrine\Persistence\ObjectManager;
use Doctrine\DBAL\Types\DateImmutableType;
use SebastianBergmann\Environment\Console;
use Doctrine\Bundle\FixturesBundle\Fixture;
use App\DataFixtures\provider\MovieDbProvider;
use App\Entity\User;
use Symfony\Component\Validator\Constraints\DateTime as ConstraintsDateTime;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $objectManager): void
    {
        $faker = Faker\Factory::create();

        // Si on veut toujours les mêmes données
        $faker->seed('MovieDB');
        // Fournisseur de données, ajouté à Faker
        $faker->addProvider(new MovieDbProvider);

        // 3 users : user, manager, admin
        $user = new User();
        $user->setEmail('user@user.com');
        $user->setPassword('$2y$13$HHc5BGzrOWdNc7GVXCMnIuaWng96Myjw0NUa/YjOhcIEw3N0sZGre');
        $user->setRoles(['ROLE_USER']);
        $objectManager->persist($user);

        $manager = new User();
        $manager->setEmail('manager@manager.com');
        $manager->setPassword('$2y$13$q3LLzsIgilR2hIVFXKb3iOVzD8RXZg827CaqsEn41zwsUGtaVGNDm');
        $manager->setRoles(['ROLE_MANAGER']);
        $objectManager->persist($manager);

        $admin = new User();
        $admin->setEmail('admin@admin.com');
        $admin->setPassword('$2y$13$yQqpoAPaC7UMMgQUI.tceeymS4.WUHxniGfybfQw77f/3EGEUv2LO');
        $admin->setRoles(['ROLE_ADMIN']);
        $objectManager->persist($admin);

        $genres = [];
        $persons = [];
        $movies = [];

        for ($i = 1; $i <= 20; $i++) {
            $genre = new Genre();
            $genre->setName($faker->unique()->movieGenre());
            $genre->setCreatedAt(new DateTimeImmutable());

            $genres[] = $genre;
            $objectManager->persist($genre);
        }

        for ($i = 1; $i <= 20; $i++) {
            $person = new Person();
            $person->setFirstname($faker->firstName());
            $person->setLastname($faker->lastName());
            $person->setCreatedAt(new DateTimeImmutable());

            $persons[] = $person;
            $objectManager->persist($person);
        }

        for ($i = 1; $i <= 20; $i++) {
            $movie = new Movie();
            $movie->setTitle($faker->unique()->movieTitle());
            $movie->setReleaseDate($faker->dateTimeBetween('-100 years'));
            $movie->setDuration($faker->numberBetween(15, 360));
            $movie->setPoster($faker->imageUrl(300, 400));
            $movie->setRating($faker->numberBetween(1, 5));

            // Association de 1 à 3 genres au hasard
            for($r = 1; $r <= mt_rand(1, 3); $r++) {
                // On va chercher un genre aléatoirement dans la liste des genres
                $movie->addGenre($genres[array_rand($genres)]);
            }

            $movies[] = $movie;
            $objectManager->persist($movie);
        }

        // for ($i = 1; $i <= 20; $i++) {
        //     $movie = $movies[$i -1];
        //     $randNumber = rand(0, 19);
        //     $movie->addGenre($genres[$randNumber]);
            
        //     $manager->persist($movie);
        // }

        foreach ($movies as $movie) {
            for ($i = 1; $i <= mt_rand(2, 4); $i++) {
                $casting = new Casting();
                $casting->setRole($faker->firstName());
                $casting->setCreditOrder($i);
                $casting->setCreatedAt(new DateTimeImmutable());
                $casting->setMovie($movie);
                $casting->setPerson($persons[array_rand($persons)]);

                $objectManager->persist($casting);
            }
        }


        $objectManager->flush();
    }
}
