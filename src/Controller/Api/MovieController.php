<?php

namespace App\Controller\Api;

use App\Repository\MovieRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MovieController extends AbstractController
{
    #[Route('/api/movies', name: 'api_movies_get', methods:'GET')]
    public function index(MovieRepository $movieRepository): Response
    {
        $movies = $movieRepository->findByOrderedByTitleAsc();
        return $this->json(
            [$movies],
            200,
            [],
            ['groups' => 'movies_get'],
        );
    }
}
