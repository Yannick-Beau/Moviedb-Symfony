<?php

namespace App\Controller\Back;

use App\Entity\Movie;
use App\Form\MovieType;
use App\Repository\MovieRepository;
use App\Repository\CastingRepository;
use App\Service\MessageGenerator;
use App\Service\MySlugger;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MovieController extends AbstractController
{
    /**
     * Lister les films
     */
    #[Route('/back/movie/browse', name: 'back_movie_browse', methods: ['GET'])]
    public function browse(MovieRepository $movieRepository): Response
    {
        $movies = $movieRepository->findByOrderedByTitleAsc();
        return $this->render('back/movie/browse.html.twig', [
            'movies' => $movies,
        ]);
    }

    /**
     * Affiche un film
     */
    #[Route('/back/movie/read/{id<\d+>}', name: 'back_movie_read', methods: ['GET'])]
    public function read(Movie $movie = null, CastingRepository $castingRepository): Response
    {
        // 404
        if ($movie === null) {
            throw $this->createNotFoundException('Film non trouvé.');
        }
        $castings = $castingRepository->findAllByMovieJoinedToPerson($movie);
        return $this->render('back/movie/read.html.twig', [
            'movie' => $movie,
            'castings' => $castings,
        ]);
    }

    /**
     * Ajouter un film
     */
    #[Route('/back/movie/add', name: 'back_movie_add', methods: ['GET', 'POST'])]
    public function add(Request $request, EntityManagerInterface $entityManager, MessageGenerator $messageGenerator, MySlugger $mySlugger): Response
    {   
        $movie = new Movie();
        // Créarion du form, associé à l'entité $review
       $form = $this->createForm(MovieType::class, $movie);

       // Prendre en charge la requête
       $form->handleRequest($request);

       if ($form->isSubmitted() && $form->isValid()) {
           // On défini le slug du film depuis son titre => transféré dans MovieListener
           //$movie->setSlug($mySlugger->slugify($movie->getTitle()));

           $entityManager->persist($movie);
           $entityManager->flush();
           $this->addFlash('success', $messageGenerator->getSuccessMessage());
           return $this->redirectToRoute('back_movie_read', ['id' => $movie->getId()]);
       }

        return $this->render('back/movie/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Editer un film
     */
    #[Route('/back/movie/edit/{id<\d+>}', name: 'back_movie_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, EntityManagerInterface $entityManager, Movie $movie = null, MessageGenerator $messageGenerator, MySlugger $mySlugger): Response
    {   
        // 404
        if ($movie === null) {
            throw $this->createNotFoundException('Film non trouvé.');
        }
        // Créarion du form, associé à l'entité $review
       $form = $this->createForm(MovieType::class, $movie);

       // Prendre en charge la requête
       $form->handleRequest($request);

       if ($form->isSubmitted() && $form->isValid()) {

           // On définit le slug du film depuis son titre => transféré dans MovieListener
           // /!\ SEO : il faudra prévoir un système de redirection de l'ancienne URL vers la nouvelle URL (avec un status 302)
           // $movie->setSlug($mySlugger->slugify($movie->getTitle()));
           
           // Pas de persit() pour un edit
           $entityManager->flush();

           $this->addFlash('success', $messageGenerator->getSuccessMessage());

           return $this->redirectToRoute('back_movie_read', ['id' => $movie->getId()]);
       }

        return $this->render('back/movie/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

     /**
     * Supprimer un film
     */
    #[Route('/back/movie/delete/{id<\d+>}', name: 'back_movie_delete', methods: ['GET'])]
    public function delete(EntityManagerInterface $entityManager, Movie $movie = null, MessageGenerator $messageGenerator): Response
    {   
        // 404
        if ($movie === null) {
            throw $this->createNotFoundException('Film non trouvé.');
        }
       $entityManager->remove($movie);
       $entityManager->flush();

       $this->addFlash('success', $messageGenerator->getSuccessMessage());

       return $this->redirectToRoute('back_movie_browse');
       

        
    }
}
