<?php

namespace App\Controller;

use App\Entity\Movie;
use App\Entity\Review;
use App\Form\ReviewType;
use Doctrine\ORM\EntityManager;
use App\Repository\MovieRepository;
use App\Repository\CastingRepository;
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
        $movies = $movieRepository->findByOrderedByTitleAsc();

        return $this->render('main/home.html.twig', [
            'movies' => $movies,
        ]);
    }
    /**
     * Affiche un film
     */
    #[Route('/movie/{id<\d+>}', name: 'movie_show')]
    public function movie(Movie $movie, CastingRepository $castingRepository): Response
    {
       if ($movie === null) {
           throw $this->createNotFoundException('Film non trouvé.');
       }

       $castings = $castingRepository->findAllByMovieJoinedToPerson($movie);
       

        return $this->render('main/movie_show.html.twig', [
            'movie' => $movie,
            'castings' => $castings,
        ]);
    }

    /**
     * Ajout d'une critique sur un film
     */
    #[Route('/movie/{id<\d+>}/add/review', name: 'movie_add_review')]
    public function movieAddReview(Movie $movie = null): Response
    {
       if ($movie === null) {
           throw $this->createNotFoundException('Film non trouvé.');
       }

       // Nouvelle critique
       $review = new Review();

       // Créarion du form, associé à l'entité $review
       $form = $this->createForm(ReviewType::class, $review);

        return $this->render('main/movie_add_review.html.twig', [
            'form' => $form->createView(),
            'movie' => $movie,
        ]);
    }


}
