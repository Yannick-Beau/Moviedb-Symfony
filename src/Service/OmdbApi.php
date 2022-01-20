<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * Service qui cause à OMDB API
 */
class OmdbApi
{
    /**
     * Client HTTP pour exécuter des requêtes
     */
    private $httpClient;

    public function __construct(HttpClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    public function fetch(string $title): array
    {
        // On exécute une requête vers OMDBAPI
        $response = $this->httpClient->request(
            'GET',
            'https://www.omdbapi.com/?apiKey=83bfb8c6&t=' . $title
        );

        // On convertit le JSON en tableau PHP
        $content = $response->toArray();
        // $content = ['id' => 521583, 'name' => 'symfony-docs', ...]

        // On retourne le tableau
        return $content;
    }

    /**
     * Renvoie l'URL du poster
     * 
     * @param string $title Titre du film
     * @return null|string
     */
    public function fetchPoster(string $title)
    {
        // Appelons la méthode fetch de notre service
        $content = $this->fetch($title);

        // Clé Poster existe ?
        if (array_key_exists('Poster', $content)) {
            return $content['Poster'];
        }

        return null;
    }
}