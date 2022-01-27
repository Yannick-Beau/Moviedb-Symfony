<?php

namespace App\Entity;

use DateTime;
use App\Entity\Review;
use Doctrine\ORM\Mapping as ORM;
// On va appliquer la logique de mapping via l'annotation @ORM
// qui correspond à un dossier "Mapping" de Doctrine
use App\Repository\MovieRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
/**
 * Classe qui représente la table "movie" et ses enregistrements
 * @ORM\Entity(repositoryClass=MovieRepository::class)
 * 
 * Cette entité va réagir aux événements "lifecycle callbacks" de Doctrine
 * https://symfony.com/doc/current/doctrine/events.html#doctrine-lifecycle-callbacks
 * @ORM\HasLifecycleCallbacks()
 * 
 * Unicité sur les propriétés $tittle et $slug
 * @UniqueEntity(fields={"title"})
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
     * @Groups("movies_get")
     */
    private $id;

    /**
     * Titre du film
     * 
     * @ORM\Column(type="string", length=211, unique=true)
     * 
     * @Assert\NotBlank
     * @Assert\Length(max = 211)
     * @Groups("movies_get")
     */
    private $title;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @ORM\Column(type="date", nullable=false)
     * @Assert\NotBlank
     * 
     * @Groups("movies_get")
     */
    private $releaseDate;

    /**
     * @ORM\Column(type="smallint", nullable=false)
     * 
     * @Assert\NotBlank
     * @Assert\Positive
     * @Assert\LessThanOrEqual(1440)
     * 
     * @Groups("movies_get")
     */
    private $duration;

    /**
     * @ORM\ManyToMany(targetEntity=Genre::class, inversedBy="movies")
     * 
     * @Assert\Count(min=1)
     * 
     * @Groups("movies_get")
     */
    private $genres;

    /**
     * @ORM\OneToMany(targetEntity=Casting::class, mappedBy="movie", orphanRemoval=true)
     * 
     * @Groups("movies_get")
     */
    private $castings;

    /**
     * @ORM\OneToMany(targetEntity=Review::class, mappedBy="movie", orphanRemoval=true)
     */
    private $reviews;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * 
     * @Groups("movies_get")
     */
    private $poster;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     * @Assert\NotBlank
     * @Assert\Type("int") 
     * @Assert\Length(max = 1)
     * @Assert\Choice({5, 4, 3, 2, 1}) 
     * @Groups("movies_get")
     */
    private $rating;

    /**
     * @ORM\OneToMany(targetEntity=Team::class, mappedBy="movie", orphanRemoval=true)
     */
    private $teams;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * 
     * @Groups("movies_get")
     */
    private $slug;


    public function __construct()
    {
        $this->createdAt = new DateTime();
        $this->releaseDate = new DateTime();
        $this->genres = new ArrayCollection();
        $this->castings = new ArrayCollection();
        $this->reviews = new ArrayCollection();
        $this->teams = new ArrayCollection();
    }
    
   
    /**
     * Get the value of createdAt
     */ 
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set the value of createdAt
     *
     * @return  self
     */ 
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get the value of updatedAt
     */ 
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set the value of updatedAt
     *
     * @return  self
     */ 
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get titre du film
     */ 
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set titre du film
     *
     * @return  self
     */ 
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get clé primaire
     */ 
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set clé primaire
     *
     * @return  self
     */ 
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    public function getReleaseDate(): ?\DateTimeInterface
    {
        return $this->releaseDate;
    }

    public function setReleaseDate(\DateTimeInterface $releaseDate): self
    {
        $this->releaseDate = $releaseDate;

        return $this;
    }

    public function getDuration(): ?string
    {
        return $this->duration;
    }

    public function setDuration(string $duration): self
    {
        $this->duration = $duration;

        return $this;
    }

    /**
     * @return Collection|Genre[]
     */
    public function getGenres(): Collection
    {
        return $this->genres;
    }

    public function addGenre(Genre $genre): self
    {
        if (!$this->genres->contains($genre)) {
            $this->genres[] = $genre;
        }

        return $this;
    }

    public function removeGenre(Genre $genre): self
    {
        $this->genres->removeElement($genre);

        return $this;
    }

    /**
     * @return Collection|Casting[]
     */
    public function getCastings(): Collection
    {
        return $this->castings;
    }

    public function addCasting(Casting $casting): self
    {
        if (!$this->castings->contains($casting)) {
            $this->castings[] = $casting;
            $casting->setMovie($this);
        }

        return $this;
    }

    public function removeCasting(Casting $casting): self
    {
        if ($this->castings->removeElement($casting)) {
            // set the owning side to null (unless already changed)
            if ($casting->getMovie() === $this) {
                $casting->setMovie(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Review[]
     */
    public function getReviews(): Collection
    {
        return $this->reviews;
    }

    public function addReview(Review $review): self
    {
        if (!$this->reviews->contains($review)) {
            $this->reviews[] = $review;
            $review->setMovie($this);
        }

        return $this;
    }

    public function removeReview(Review $review): self
    {
        if ($this->reviews->removeElement($review)) {
            // set the owning side to null (unless already changed)
            if ($review->getMovie() === $this) {
                $review->setMovie(null);
            }
        }

        return $this;
    }

    public function getPoster(): ?string
    {
        return $this->poster;
    }

    public function setPoster(?string $poster): self
    {
        $this->poster = $poster;

        return $this;
    }

    public function getRating(): ?int
    {
        return $this->rating;
    }

    public function setRating(?int $rating): self
    {
        $this->rating = $rating;

        return $this;
    }

    /**
     * @return Collection|Team[]
     */
    public function getTeams(): Collection
    {
        return $this->teams;
    }

    public function addTeam(Team $team): self
    {
        if (!$this->teams->contains($team)) {
            $this->teams[] = $team;
            $team->setMovie($this);
        }

        return $this;
    }

    public function removeTeam(Team $team): self
    {
        if ($this->teams->removeElement($team)) {
            // set the owning side to null (unless already changed)
            if ($team->getMovie() === $this) {
                $team->setMovie(null);
            }
        }

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Exécute cette méthode avant l'update de l'entité en BDD
     * /!\ Géré en interne par Doctrine
     * @ORM\PreUpdate
     */
    public function setUpdatedAtValueToNow()
    {
        $this->updatedAt = new DateTime();
    }

    
}