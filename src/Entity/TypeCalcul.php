<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TypeCalcul
 *
 * @ORM\Table(name="type_calcul")
 * @ORM\Entity
 */
class TypeCalcul
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_calcul", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idCalcul;

    /**
     * @var string
     *
     * @ORM\Column(name="libelle_calcul", type="string", length=255, nullable=false)
     */
    private $libelleCalcul;

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

    public function getIdCalcul(): ?int
    {
        return $this->idCalcul;
    }

    public function getLibelleCalcul(): ?string
    {
        return $this->libelleCalcul;
    }

    public function setLibelleCalcul(string $libelleCalcul): self
    {
        $this->libelleCalcul = $libelleCalcul;

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
