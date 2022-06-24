<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TypeMatiere
 *
 * @ORM\Table(name="type_matiere")
 * @ORM\Entity
 */
class TypeMatiere
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_type_matiere", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idTypeMatiere;

    /**
     * @var string
     *
     * @ORM\Column(name="code_type_matiere", type="string", length=255, nullable=false)
     */
    private $codeTypeMatiere;

    /**
     * @var string
     *
     * @ORM\Column(name="libelle_type_matiere", type="string", length=255, nullable=false)
     */
    private $libelleTypeMatiere;

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

    public function getIdTypeMatiere(): ?int
    {
        return $this->idTypeMatiere;
    }

    public function getCodeTypeMatiere(): ?string
    {
        return $this->codeTypeMatiere;
    }

    public function setCodeTypeMatiere(string $codeTypeMatiere): self
    {
        $this->codeTypeMatiere = $codeTypeMatiere;

        return $this;
    }

    public function getLibelleTypeMatiere(): ?string
    {
        return $this->libelleTypeMatiere;
    }

    public function setLibelleTypeMatiere(string $libelleTypeMatiere): self
    {
        $this->libelleTypeMatiere = $libelleTypeMatiere;

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
