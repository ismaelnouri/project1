<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Venu
 *
 * @ORM\Table(name="venu")
 * @ORM\Entity
 */
class Venu
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_venu", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idVenu;

    /**
     * @var string
     *
     * @ORM\Column(name="libelle_venu", type="string", length=255, nullable=false)
     */
    private $libelleVenu;

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

    public function getIdVenu(): ?int
    {
        return $this->idVenu;
    }

    public function getLibelleVenu(): ?string
    {
        return $this->libelleVenu;
    }

    public function setLibelleVenu(string $libelleVenu): self
    {
        $this->libelleVenu = $libelleVenu;

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
