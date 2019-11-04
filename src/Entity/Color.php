<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ColorRepository")
 */
class Color
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $color;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Sneaker", inversedBy="colors")
     */
    private $sneaker;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $color_sneaker_path;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(string $color): self
    {
        $this->color = $color;

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

    public function getColorSneakerPath(): ?string
    {
        return $this->color_sneaker_path;
    }

    public function setColorSneakerPath(string $color_sneaker_path): self
    {
        $this->color_sneaker_path = $color_sneaker_path;

        return $this;
    }

    public function __toString()
    {
        return $this->color;
    }
}
