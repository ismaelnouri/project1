<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TypeDevoir
 *
 * @ORM\Table(name="type_devoir")
 * @ORM\Entity
 */
class TypeDevoir
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_type_devoir", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idTypeDevoir;

    /**
     * @var string
     *
     * @ORM\Column(name="libelle_type_devoir", type="string", length=255, nullable=false)
     */
    private $libelleTypeDevoir;

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

    public function getIdTypeDevoir(): ?int
    {
        return $this->idTypeDevoir;
    }

    public function getLibelleTypeDevoir(): ?string
    {
        return $this->libelleTypeDevoir;
    }

    public function setLibelleTypeDevoir(string $libelleTypeDevoir): self
    {
        $this->libelleTypeDevoir = $libelleTypeDevoir;

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
