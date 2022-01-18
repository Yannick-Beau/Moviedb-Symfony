<?php

namespace App\Controller;

use App\Entity\Movie;
use App\Entity\Review;
use App\Form\ReviewType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\MovieRepository;
use App\Repository\CastingRepository;
use App\Repository\GenreRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MainController extends AbstractController
{
    /**
     * Liste les films
     */
    #[Route('/', name: 'home')]
    public function home(MovieRepository $movieRepository, GenreRepository $genreRepository): Response
    {
        $movies = $movieRepository->findByOrderedByTitleAsc();
        $genres = $genreRepository->findBy([], ['name' => 'ASC']);

        return $this->render('front/main/home.html.twig', [
            'movies' => $movies,
            'genres' => $genres,
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
       

        return $this->render('front/main/movie_show.html.twig', [
            'movie' => $movie,
            'castings' => $castings,
        ]);
    }

    /**
     * Ajout d'une critique sur un film
     */
    #[Route('/movie/{id<\d+>}/add/review', name: 'movie_add_review', methods: ['GET', 'POST'])]
    public function movieAddReview(Movie $movie = null, Request $request, EntityManagerInterface $entityManager): Response
    {
       if ($movie === null) {
           throw $this->createNotFoundException('Film non trouvé.');
       }

       // Nouvelle critique
       $review = new Review();

       // Créarion du form, associé à l'entité $review
       $form = $this->createForm(ReviewType::class, $review);

       // Prendre en charge la requête
       $form->handleRequest($request);

       if ($form->isSubmitted() && $form->isValid()) {
           $review->setMovie($movie);
           $entityManager->persist($review);
           $entityManager->flush();
           return $this->redirectToRoute('movie_show', ['id' => $movie->getId()]);
       }

        return $this->render('front/main/movie_add_review.html.twig', [
            'form' => $form->createView(),
            'movie' => $movie,
        ]);
    }


}
