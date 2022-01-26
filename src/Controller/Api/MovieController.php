<?php

namespace App\Controller\Api;

use App\Entity\Movie;
use App\Repository\MovieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MovieController extends AbstractController
{
    #[Route('/api/movies', name: 'api_movies_get', methods:'GET')]
    public function getCollection(MovieRepository $movieRepository): Response
    {
        $movies = $movieRepository->findByOrderedByTitleAsc();
        return $this->json(
            [$movies],
            200,
            [],
            ['groups' => 'movies_get'],
        );
    }

    #[Route('/api/movies/{id<\d+>}', name: 'api_movies_get_item', methods:'GET')]
    public function show(Movie $movie): Response
    {
        // /!\ JSON Hijacking
        // @see https://symfony.com/doc/current/components/http_foundation.html#creating-a-json-response
        return $this->json($movie, Response::HTTP_OK, [], ['groups' => 'movies_get']);
    }

    #[Route('/api/movies', name: 'api_movies_post', methods:'POST')]
    public function postItem(Request $request, SerializerInterface $serializer, EntityManagerInterface $entityManager, ValidatorInterface $validator): Response
    {
        
         // On récupère le contenu de la requête (du JSON)
        $jsonContent = $request->getContent();

        // On désérialise le JSON vers une entité Movie
        // @see https://symfony.com/doc/current/components/serializer.html#deserializing-an-object
        $movie = $serializer->deserialize($jsonContent, Movie::class, 'json');
        // On valide l'entité avec le service Validator
        $errors = $validator->validate($movie);
        // Si la validation rencontre des erreurs
        // ($errors se comporte comme un tableau et contient un élément par erreur)
        if (count($errors) > 0) {
            return $this->json(['errors' => $errors], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        
        // On persist, on flush
        $entityManager->persist($movie);
        $entityManager->flush();

        // REST nous demande un statut 201 et un header Location: url
        // Si on le fait "à la mano"
        return $this->json(
            // Le film que l'on retourne en JSON directement au front
            $movie,
            // Le status code
            // C'est cool d'utiliser les constantes de classe !
            // => ça aide à la lecture du code et au fait de penser objet
            Response::HTTP_CREATED,
            // Un header Location + l'URL de la ressource créée
            ['Location' => $this->generateUrl('api_movies_get_item', ['id' => $movie->getId()])],
            // Le groupe de sérialisation pour que $movie soit sérialisé sans erreur de référence circulaire
            ['groups' => 'movies_get']
        );
    }
}
