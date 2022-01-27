<?php

namespace App\Controller\Api;

use App\Entity\Movie;
use App\Repository\MovieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
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
    public function getItem(Movie $movie): Response
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

    #[Route('/api/movies/{id<\d+>}', name: 'api_movies_put_item', methods:['PUT', 'PATH'])]
    public function putItem(Movie $movie = null, SerializerInterface $serializer, ValidatorInterface $validator, EntityManagerInterface $entityManager, Request $request): Response
    {
        // Film non trouvé
        if ($movie === null) {
            return new JsonResponse(
                ["message" => "Film non trouvé"],
                Response::HTTP_NOT_FOUND
            );
        }
        // Récupère les données de la requête
        $data = $request->getContent();

        // @todo Pour PUT, s'assurer qu'on ait un certain nombre de champs
        // @todo Pour PATCH, s'assurer qu'on au moins un champ
        // sinon => 422 HTTP_UNPROCESSABLE_ENTITY

        // On désérialise le JSON vers *l'entité Movie existante*
        // @see https://symfony.com/doc/current/components/serializer.html#deserializing-in-an-existing-object
        $movie = $serializer->deserialize($data, Movie::class, 'json', [AbstractNormalizer::OBJECT_TO_POPULATE => $movie]);

        // On valide l'entité
        $errors = $validator->validate($movie);

        // Affichage des erreurs
        if (count($errors) > 0) {
            // On va créer un joli tableau d'erreurs
            $newErrors = [];
            // Pour chaque erreur
            foreach ($errors as $error) {
                // Astuce ici ! on poush dans un taleau
                // = similaire à la structure des Flash Messages
                // On push le message, à la clé qui contient la propriété
                $newErrors[$error->getPropertyPath()][] = $error->getMessage();
            }

            return new JsonResponse(["errors" => $newErrors], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        // Enregistrement en BDD
        $entityManager->flush();

        // @todo Conditionner le message de retour au cas où
        // l'entité ne serait pas modifiée
        return new JsonResponse(["message" => "Film modifié"], Response::HTTP_OK);
    }

    #[Route('/api/movies/{id<\d+>}', name: 'api_movies_delete_item', methods:'DELETE')]
    public function deleteItem(Movie $movie = null, EntityManagerInterface $em)
    {
        if (null === $movie) {

            $error = 'Ce film n\'existe pas';

            return $this->json(['error' => $error], Response::HTTP_NOT_FOUND);
        }

        $em->remove($movie);
        $em->flush();

        return $this->json(['message' => 'Le film a bien été supprimé.'], Response::HTTP_OK);
    }

}
