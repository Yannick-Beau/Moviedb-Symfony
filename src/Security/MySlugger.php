<?php

namespace App\Service;

use Symfony\Component\String\Slugger\SluggerInterface;

/**
 * Un service pour configurer la façon dont on va "slugifier"
 */
class MySlugger
{
    /**
     * Le service Slugger de Symfony
     */
    private $slugger;

    /**
     * Le slug doit-il être en lowercase ?
     * => comment se comporte mon service ?
     */
    private $toLower;

    /**
     * Récupération du(des) service(s)
     */
    public function __construct(SluggerInterface $slugger, bool $toLower)
    {
        $this->slugger = $slugger;
        $this->toLower = $toLower;
    }

    /**
     * "Slugifie" une chaine donnée
     * 
     * @param string $stringToSlug La chaine à "slugifier"
     * @return string La chaine slugifiée
     */
    public function slugify(string $stringToSlug): string
    {
        // Lowercase ou non ?
        if ($this->toLower === true) {
            // Oui
            return $this->slugger->slug($stringToSlug)->lower();
        } else {
            // Non
            return $this->slugger->slug($stringToSlug);
        }
    }
}