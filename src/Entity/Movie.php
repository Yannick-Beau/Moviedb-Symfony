<?php

namespace App\Entity;

// On va appliquer la logique de mapping via l'annotation @ORM
// qui correspond à un dossier "Mapping" de Doctrine
use Doctrine\ORM\Mapping as ORM;

/**
 * Classe qui représente la table "movie" et ses enregistrements
 * 
 * @ORM\Entity
 */
class Movie {
    /**
     * Clé primaire
     * Auto-increment
     * tpe INT
     * 
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * Titre du film
     * 
     * @ORM\Column(type="string", length=211)
     */
    private $title;
    
    /**
     * Get clé primaire
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Get Titre du film
     */
    public function getTitle() {
        return $this->title;
    }

}