<?php

namespace App\Entity;

use App\Repository\CastingRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CastingRepository::class)
 */
class Casting
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=30)
     */
    private $role;

    /**
     * @ORM\Column(type="smallint")
     */
    private $creditOrder;

    /**
     * @ORM\ManyToOne(targetEntity=Movie::class, inversedBy="castings")
     * @ORM\JoinColumn(nullable=false)
     */
    private $movieId;

    /**
     * @ORM\ManyToOne(targetEntity=Person::class, inversedBy="castings")
     * @ORM\JoinColumn(nullable=false)
     */
    private $person;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(string $role): self
    {
        $this->role = $role;

        return $this;
    }

    public function getCreditOrder(): ?int
    {
        return $this->creditOrder;
    }

    public function setCreditOrder(int $creditOrder): self
    {
        $this->creditOrder = $creditOrder;

        return $this;
    }

    public function getMovieId(): ?Movie
    {
        return $this->movieId;
    }

    public function setMovieId(?Movie $movieId): self
    {
        $this->movieId = $movieId;

        return $this;
    }

    public function getPerson(): ?Person
    {
        return $this->person;
    }

    public function setPerson(?Person $person): self
    {
        $this->person = $person;

        return $this;
    }
}
