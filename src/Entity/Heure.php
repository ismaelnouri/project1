<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Heure
 *
 * @ORM\Table(name="heure")
 * @ORM\Entity
 */
class Heure
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_heure", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idHeure;

    /**
     * @var string
     *
     * @ORM\Column(name="libelle_heure", type="string", length=255, nullable=false)
     */
    private $libelleHeure;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_ajout", type="date", nullable=false)
     */
    private $dateAjout;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="date_sup", type="datetime", nullable=true)
     */
    private $dateSup;

    /**
     * @var int
     *
     * @ORM\Column(name="statut", type="integer", nullable=false)
     */
    private $statut;

    public function getIdHeure(): ?int
    {
        return $this->idHeure;
    }

    public function getLibelleHeure(): ?string
    {
        return $this->libelleHeure;
    }

    public function setLibelleHeure(string $libelleHeure): self
    {
        $this->libelleHeure = $libelleHeure;

        return $this;
    }

    public function getDateAjout(): ?\DateTimeInterface
    {
        return $this->dateAjout;
    }

    public function setDateAjout(\DateTimeInterface $dateAjout): self
    {
        $this->dateAjout = $dateAjout;

        return $this;
    }

    public function getDateSup(): ?\DateTimeInterface
    {
        return $this->dateSup;
    }

    public function setDateSup(?\DateTimeInterface $dateSup): self
    {
        $this->dateSup = $dateSup;

        return $this;
    }

    public function getStatut(): ?int
    {
        return $this->statut;
    }

    public function setStatut(int $statut): self
    {
        $this->statut = $statut;

        return $this;
    }


}
