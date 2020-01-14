<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SneakerColor
 * @ORM\Table(name="sneaker_color")
 * @ORM\Entity(repositoryClass="App\Repository\SneakerColorRepository")
 */
class SneakerColor
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
    private $colorsneaker_path;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Sneaker", inversedBy="sneakerColors")
     */
    private $sneaker;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Color", inversedBy="sneakerColors")
     */
    private $colors;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getColorsneakerPath(): ?string
    {
        return $this->colorsneaker_path;
    }

    public function setColorsneakerPath(string $colorsneaker_path): self
    {
        $this->colorsneaker_path = $colorsneaker_path;

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

    public function getColors(): ?Color
    {
        return $this->colors;
    }

    public function setColors(?Color $colors): self
    {
        $this->colors = $colors;

        return $this;
    }
}
