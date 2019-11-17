<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Vich\UploaderBundle\Mapping\Annotation as Vich;


/**
 * Sneaker
 * @Vich\Uploadable
 * @ORM\Table(name="sneaker")
 * @ORM\Entity(repositoryClass="App\Repository\SneakerRepository")
 */
class Sneaker
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;


    /**
     * @var string
     *
     * @ORM\Column(name="modele", type="string", length=255, nullable=false)
     */
    private $modele;

    /**
     * @var string
     *
     * @ORM\Column(name="titre", type="string", length=255, nullable=false)
     */
    private $titre;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=1000, nullable=false)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="marque", type="string", length=255, nullable=false)
     */
    private $marque;

    /**
     * @var float
     *
     * @ORM\Column(name="prix", type="float", precision=10, scale=0, nullable=false)
     */
    private $prix;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Taille", inversedBy="sneakers")
     */
    private $taille;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Commande", mappedBy="sneakers")
     */
    private $commandes;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Favoris", mappedBy="sneaker")
     */
    private $favoris;
    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Quantity", mappedBy="chaussure")
     */
    private $quantities;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Avis", mappedBy="sneaker")
     */
    private $avis;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $added_at;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Color", mappedBy="sneaker", cascade={"persist"})
     */
    private $colors;


    /**
     *
     * @Vich\UploadableField(mapping="sneaker_image", fileNameProperty="imageName", size="imageSize")
     *
     * @var File
     */
    private $imageFile;



    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     *
     * @var string|null
     */
    private $imageName;

    /**
     * @param string $imageName
     */
    public function setImageName(string $imageName): void
    {
        $this->imageName = $imageName;
    }

    /**
     * @param int $imageSize
     */
    public function setImageSize(int $imageSize): void
    {
        $this->imageSize = $imageSize;
    }

    /**
     * @return string|null
     */
    public function getImageName()
    {
        return $this->imageName;
    }

    /**
     * @return int
     */
    public function getImageSize(): int
    {
        return $this->imageSize;
    }

    /**
     * @return mixed
     */
    public function getUpdateAt()
    {
        return $this->update_at;
    }

    /**
     * @ORM\Column(type="integer")
     *
     * @var integer
     */
    private $imageSize;

    /**
     * @ORM\Column(type="datetime" , nullable=true)
     */
    private $update_at;





    public function __construct()
    {
        $this->commandes = new ArrayCollection();
        $this->taille = new ArrayCollection();
        $this->favoris = new ArrayCollection();
        $this->quantities = new ArrayCollection();
        $this->quantityOrders = new ArrayCollection();
        $this->avis = new ArrayCollection();
        $this->colors = new ArrayCollection();

    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCouleur(): ?string
    {
        return $this->couleur;
    }

    public function setCouleur(string $couleur): self
    {
        $this->couleur = $couleur;

        return $this;
    }


    public function setTaille(float $taille): self
    {
        $this->taille = $taille;

        return $this;
    }

    public function getModele(): ?string
    {
        return $this->modele;
    }

    public function setModele(string $modele): self
    {
        $this->modele = $modele;

        return $this;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getMarque(): ?string
    {
        return $this->marque;
    }

    public function setMarque(string $marque): self
    {
        $this->marque = $marque;

        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function setPath(string $path): self
    {
        $this->path = $path;

        return $this;
    }



    public function setCommande(?Commande $commande): self
    {
        $this->commande = $commande;

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
            $commande->setChaussures($this);
        }

        return $this;
    }

    public function removeCommande(Commande $commande): self
    {
        if ($this->commandes->contains($commande)) {
            $this->commandes->removeElement($commande);
            // set the owning side to null (unless already changed)
            if ($commande->getChaussures() === $this) {
                $commande->setChaussures(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Taille[]
     */
    public function getTaille(): Collection
    {
        return $this->taille;
    }

    public function addTaille(Taille $taille): self
    {
        if (!$this->taille->contains($taille)) {
            $this->taille[] = $taille;
        }

        return $this;
    }

    public function removeTaille(Taille $taille): self
    {
        if ($this->taille->contains($taille)) {
            $this->taille->removeElement($taille);
        }

        return $this;
    }

    /**
     * @return Collection|Favoris[]
     */
    public function getFavoris(): Collection
    {
        return $this->favoris;
    }

    public function addFavori(Favoris $favori): self
    {
        if (!$this->favoris->contains($favori)) {
            $this->favoris[] = $favori;
            $favori->addSneaker($this);
        }

        return $this;
    }

    public function removeFavori(Favoris $favori): self
    {
        if ($this->favoris->contains($favori)) {
            $this->favoris->removeElement($favori);
            $favori->removeSneaker($this);
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
            $quantity->setChaussure($this);
        }

        return $this;
    }

    public function removeQuantity(Quantity $quantity): self
    {
        if ($this->quantities->contains($quantity)) {
            $this->quantities->removeElement($quantity);
            // set the owning side to null (unless already changed)
            if ($quantity->getChaussure() === $this) {
                $quantity->setChaussure(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->titre;
    }

    /**
     * @return Collection|Avis[]
     */
    public function getAvis(): Collection
    {
        return $this->avis;
    }

    public function addAvi(Avis $avi): self
    {
        if (!$this->avis->contains($avi)) {
            $this->avis[] = $avi;
            $avi->setSneaker($this);
        }

        return $this;
    }

    public function removeAvi(Avis $avi): self
    {
        if ($this->avis->contains($avi)) {
            $this->avis->removeElement($avi);
            // set the owning side to null (unless already changed)
            if ($avi->getSneaker() === $this) {
                $avi->setSneaker(null);
            }
        }

        return $this;
    }

    public function getAddedAt(): ?\DateTimeInterface
    {
        return $this->added_at;
    }

    public function setAddedAt(\DateTimeInterface $added_at): self
    {
        $this->added_at = $added_at;

        return $this;
    }

    /**
     * @return Collection|Color[]
     */
    public function getColors(): Collection
    {
        return $this->colors;
    }

    public function addColor(Color $color): self
    {
        if (!$this->colors->contains($color)) {
            $this->colors[] = $color;
            $color->setSneaker($this);
        }

        return $this;
    }

    public function removeColor(Color $color): self
    {
        if ($this->colors->contains($color)) {
            $this->colors->removeElement($color);
            // set the owning side to null (unless already changed)
            if ($color->getSneaker() === $this) {
                $color->setSneaker(null);
            }
        }

        return $this;
    }

    /**
     * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
     * of 'UploadedFile' is injected into this setter to trigger the update. If this
     * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
     * must be able to accept an instance of 'File' as the bundle will inject one here
     * during Doctrine hydration.
     *
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile $imageFile
     */
    public function setImageFile(?File $imageFile = null): void
    {
        $this->imageFile = $imageFile;

        if ($this->imageFile instanceof UploadedFile) {
            $this->update_at = new \DateTime('now');
        }
    }


    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }



}
