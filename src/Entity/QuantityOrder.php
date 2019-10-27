<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\QuantityOrderRepository")
 */
class QuantityOrder
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="array")
     */
    private $quantity;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Sneaker", inversedBy="quantityOrders")
     */
    private $chaussure;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\taille", inversedBy="quantityOrders")
     */
    private $taille;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Commande", inversedBy="quantityOrders")
     */
    private $order_u;



    public function __construct()
    {

        $this->chaussure = new ArrayCollection();
        $this->taille = new ArrayCollection();
        $this->order_u = new ArrayCollection();
    }



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(array $quantity): self
    {
        $this->quantity[] = $quantity;

        return $this;
    }

    /**
     * @return Collection|Sneaker[]
     */
    public function getChaussure(): Collection
    {
        return $this->chaussure;
    }

    public function addChaussure(Sneaker $chaussure): self
    {
        if (!$this->chaussure->contains($chaussure)) {
            $this->chaussure[] = $chaussure;
        }

        return $this;
    }

    public function removeChaussure(Sneaker $chaussure): self
    {
        if ($this->chaussure->contains($chaussure)) {
            $this->chaussure->removeElement($chaussure);
        }

        return $this;
    }

    /**
     * @return Collection|taille[]
     */
    public function getTaille(): Collection
    {
        return $this->taille;
    }

    public function addTaille(taille $taille): self
    {
        if (!$this->taille->contains($taille)) {
            $this->taille[] = $taille;
        }

        return $this;
    }

    public function removeTaille(taille $taille): self
    {
        if ($this->taille->contains($taille)) {
            $this->taille->removeElement($taille);
        }

        return $this;
    }

    /**
     * @return Collection|Commande[]
     */
    public function getOrderU(): Collection
    {
        return $this->order_u;
    }

    public function addOrderU(Commande $orderU): self
    {
        if (!$this->order_u->contains($orderU)) {
            $this->order_u[] = $orderU;
        }

        return $this;
    }

    public function removeOrderU(Commande $orderU): self
    {
        if ($this->order_u->contains($orderU)) {
            $this->order_u->removeElement($orderU);
        }

        return $this;
    }


}
