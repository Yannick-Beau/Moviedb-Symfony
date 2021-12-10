<?php

namespace App\DataFixtures;

use App\Entity\Movie;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for ($i = 1; $i <= 20; $i++) {
            $movie = new Movie();
            $movie->setTitle('Film #' . $i);
            $movie->setReleaseDate(new DateTime());
            $movie->setDuration('1h ' . $i . 'min');
            $movie->setCreatedAt(new DateTime());

            $manager->persist($movie);
        }

        $manager->flush();
    }
}
