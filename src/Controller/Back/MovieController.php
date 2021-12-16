<?php

namespace App\Controller\Back;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MovieController extends AbstractController
{
    /**
     * Lister les films
     */
    #[Route('/back/movie/browse', name: 'back_movie_browse', methods: ['GET'])]
    public function browse(): Response
    {
        return $this->render('back/movie/index.html.twig', [
            'controller_name' => 'MovieController',
        ]);
    }
}
