<?php

namespace App\Controller;

use App\Entity\Movie;
use App\Repository\MovieRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MainController extends AbstractController
{
    /**
     * Liste les films
     */
    #[Route('/', name: 'home')]
    public function home(MovieRepository $movieRepository): Response
    {
        $movies = $movieRepository->findBy(
            [],
            ['title' => 'ASC']
        );

        return $this->render('main/home.html.twig', [
            'movies' => $movies,
        ]);
    }
    /**
     * Affiche un film
     */
    #[Route('/movie/{id<\d+>}', name: 'movie_show')]
    public function movie(Movie $movie): Response
    {
       if ($movie === null) {
           throw $this->createNotFoundException('Film non trouvé.');
       }

        return $this->render('main/movie_show.html.twig', [
            'movie' => $movie,
        ]);
    }


}
