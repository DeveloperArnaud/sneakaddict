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
     * @ORM\ManyToMany(targetEntity="App\Entity\Sneaker", mappedBy="taille")
     */
    private $sneakers;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Commande", mappedBy="tailles")
     */
    private $commandes;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Quantity", mappedBy="tailles")
     */
    private $quantities;



    public function __construct()
    {
        $this->sneakers = new ArrayCollection();
        $this->commandes = new ArrayCollection();
        $this->quantities = new ArrayCollection();

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
    public function getSneakers(): Collection
    {
        return $this->sneakers;
    }


    public function __toString()
    {
        return (string)$this->taille;
    }

    public function addSneaker(Sneaker $sneaker): self
    {
        if (!$this->sneakers->contains($sneaker)) {
            $this->sneakers[] = $sneaker;
            $sneaker->addTaille($this);
        }

        return $this;
    }

    public function removeSneaker(Sneaker $sneaker): self
    {
        if ($this->sneakers->contains($sneaker)) {
            $this->sneakers->removeElement($sneaker);
            $sneaker->removeTaille($this);
        }

        return $this;
    }

    /**
     * @return Collection|Commande[]
     */
    public function getCommandes(): Collection
    {
        return $this->commandes;
    }

    public function addCommande(Commande $commande): self
    {
        if (!$this->commandes->contains($commande)) {
            $this->commandes[] = $commande;
            $commande->addTaille($this);
        }

        return $this;
    }

    public function removeCommande(Commande $commande): self
    {
        if ($this->commandes->contains($commande)) {
            $this->commandes->removeElement($commande);
            $commande->removeTaille($this);
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

    public function addQuantity(Quantity $quantity): self
    {
        if (!$this->quantities->contains($quantity)) {
            $this->quantities[] = $quantity;
            $quantity->setTailles($this);
        }

        return $this;
    }

    public function removeQuantity(Quantity $quantity): self
    {
        if ($this->quantities->contains($quantity)) {
            $this->quantities->removeElement($quantity);
            // set the owning side to null (unless already changed)
            if ($quantity->getTailles() === $this) {
                $quantity->setTailles(null);
            }
        }

        return $this;
    }



}
