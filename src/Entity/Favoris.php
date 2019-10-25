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
     * @ORM\ManyToMany(targetEntity="App\Entity\Sneaker", inversedBy="favoris")
     */
    private $sneaker;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\User", inversedBy="favoris")
     */
    private $user;

    public function __construct()
    {
        $this->sneaker = new ArrayCollection();
        $this->user = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection|Sneaker[]
     */
    public function getSneaker(): Collection
    {
        return $this->sneaker;
    }

    public function addSneaker(Sneaker $sneaker): self
    {
        if (!$this->sneaker->contains($sneaker)) {
            $this->sneaker[] = $sneaker;
        }

        return $this;
    }

    public function removeSneaker(Sneaker $sneaker): self
    {
        if ($this->sneaker->contains($sneaker)) {
            $this->sneaker->removeElement($sneaker);
        }

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getUser(): Collection
    {
        return $this->user;
    }

    public function addUser(User $user): self
    {
        if (!$this->user->contains($user)) {
            $this->user[] = $user;
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->user->contains($user)) {
            $this->user->removeElement($user);
        }

        return $this;
    }
}
