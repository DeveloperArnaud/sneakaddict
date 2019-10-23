<?php


namespace App\Entity;


class SneakerSearch
{
    /**
     * @var string|null
     */
    private $couleur;

    /**
     * @param string|null $couleur
     */
    public function setCouleur(?string $couleur): void
    {
        $this->couleur = $couleur;
    }

    /**
     * @param int|null $taille
     */
    public function setTaille(?int $taille): void
    {
        $this->taille = $taille;
    }

    /**
     * @return string|null
     */
    public function getCouleur(): ?string
    {
        return $this->couleur;
    }

    /**
     * @return int|null
     */
    public function getTaille(): ?int
    {
        return $this->taille;
    }


    /**
     * @var int|null
     */
    private $taille;

}