<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TailleRepository")
 */
class Taille
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="float")
     */
    private $taille;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Sneaker", inversedBy="tailles")
     */
    private $sneaker;




    public function __construct()
    {
        $this->sneaker = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTaille(): ?float
    {
        return $this->taille;
    }

    public function setTaille(float $taille): self
    {
        $this->taille = $taille;

        return $this;
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

    public function __toString()
    {
        return (string)$this->taille;
    }

    /**
     * @return Collection|Sneaker[]
     */
    public function getQuantity(): Collection
    {
        return $this->quantity;
    }

    public function addQuantity(Sneaker $quantity): self
    {
        if (!$this->quantity->contains($quantity)) {
            $this->quantity[] = $quantity;
            $quantity->addQuantity($this);
        }

        return $this;
    }

    public function removeQuantity(Sneaker $quantity): self
    {
        if ($this->quantity->contains($quantity)) {
            $this->quantity->removeElement($quantity);
            $quantity->removeQuantity($this);
        }

        return $this;
    }

    /**
     * @return Collection|Quantity[]
     */
    public function getQuantities(): Collection
    {
        return $this->quantities;
    }
}
