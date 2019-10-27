<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\QuantityRepository")
 */
class Quantity
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $quantity;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Sneaker", inversedBy="quantities")
     */
    private $chaussure;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Taille", inversedBy="quantities")
     */
    private $tailles;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getChaussure(): ?Sneaker
    {
        return $this->chaussure;
    }

    public function setChaussure(?Sneaker $chaussure): self
    {
        $this->chaussure = $chaussure;

        return $this;
    }

    public function getTailles(): ?Taille
    {
        return $this->tailles;
    }

    public function setTailles(?Taille $tailles): self
    {
        $this->tailles = $tailles;

        return $this;
    }
}
