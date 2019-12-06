<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\FavorisRepository")
 */
class Favoris
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="favoris")
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Sneaker", inversedBy="favoris")
     */
    private $sneaker;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getSneaker(): ?Sneaker
    {
        return $this->sneaker;
    }

    public function setSneaker(?Sneaker $sneaker): self
    {
        $this->sneaker = $sneaker;

        return $this;
    }



}
