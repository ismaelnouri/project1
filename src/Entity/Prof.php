<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Prof
 *
 * @ORM\Table(name="prof")
 * @ORM\Entity
 */
class Prof
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_prof", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idProf;

    /**
     * @var string
     *
     * @ORM\Column(name="nom_prof", type="string", length=255, nullable=false)
     */
    private $nomProf;

    /**
     * @var string
     *
     * @ORM\Column(name="prenom_prof", type="string", length=255, nullable=false)
     */
    private $prenomProf;

    /**
     * @var string
     *
     * @ORM\Column(name="tel_prof", type="string", length=255, nullable=false)
     */
    private $telProf;

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

    public function getIdProf(): ?int
    {
        return $this->idProf;
    }

    public function getNomProf(): ?string
    {
        return $this->nomProf;
    }

    public function setNomProf(string $nomProf): self
    {
        $this->nomProf = $nomProf;

        return $this;
    }

    public function getPrenomProf(): ?string
    {
        return $this->prenomProf;
    }

    public function setPrenomProf(string $prenomProf): self
    {
        $this->prenomProf = $prenomProf;

        return $this;
    }

    public function getTelProf(): ?string
    {
        return $this->telProf;
    }

    public function setTelProf(string $telProf): self
    {
        $this->telProf = $telProf;

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
