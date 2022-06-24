<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Eleve
 *
 * @ORM\Table(name="eleve")
 * @ORM\Entity
 */
class Eleve
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_eleve", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idEleve;

    /**
     * @var string
     *
     * @ORM\Column(name="matricule_eleve", type="string", length=255, nullable=false)
     */
    private $matriculeEleve;

    /**
     * @var string
     *
     * @ORM\Column(name="nom_eleve", type="string", length=255, nullable=false)
     */
    private $nomEleve;

    /**
     * @var string
     *
     * @ORM\Column(name="prenom_eleve", type="string", length=255, nullable=false)
     */
    private $prenomEleve;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="datenais_eleve", type="date", nullable=true)
     */
    private $datenaisEleve;

    /**
     * @var string|null
     *
     * @ORM\Column(name="lieunais_eleve", type="string", length=255, nullable=true)
     */
    private $lieunaisEleve;

    /**
     * @var string|null
     *
     * @ORM\Column(name="tel_eleve", type="string", length=255, nullable=true)
     */
    private $telEleve;

    /**
     * @var string|null
     *
     * @ORM\Column(name="nationalite_eleve", type="string", length=255, nullable=true)
     */
    private $nationaliteEleve;

    /**
     * @var string|null
     *
     * @ORM\Column(name="sexe", type="string", length=1, nullable=true)
     */
    private $sexe;


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

    public function getIdEleve(): ?int
    {
        return $this->idEleve;
    }

    public function getMatriculeEleve(): ?string
    {
        return $this->matriculeEleve;
    }

    public function setMatriculeEleve(string $matriculeEleve): self
    {
        $this->matriculeEleve = $matriculeEleve;

        return $this;
    }

    public function getNomEleve(): ?string
    {
        return $this->nomEleve;
    }

    public function setNomEleve(string $nomEleve): self
    {
        $this->nomEleve = $nomEleve;

        return $this;
    }

    public function getPrenomEleve(): ?string
    {
        return $this->prenomEleve;
    }

    public function setPrenomEleve(string $prenomEleve): self
    {
        $this->prenomEleve = $prenomEleve;

        return $this;
    }

    public function getDatenaisEleve(): ?\DateTimeInterface
    {
        return $this->datenaisEleve;
    }

    public function setDatenaisEleve(?\DateTimeInterface $datenaisEleve): self
    {
        $this->datenaisEleve = $datenaisEleve;

        return $this;
    }

    public function getLieunaisEleve(): ?string
    {
        return $this->lieunaisEleve;
    }

    public function setLieunaisEleve(?string $lieunaisEleve): self
    {
        $this->lieunaisEleve = $lieunaisEleve;

        return $this;
    }

    public function getTelEleve(): ?string
    {
        return $this->telEleve;
    }

    public function setTelEleve(?string $telEleve): self
    {
        $this->telEleve = $telEleve;

        return $this;
    }

     public function getSexe(): ?string
    {
        return $this->sexe;
    }

    public function setSexe(string $sexe): self
    {
        $this->sexe = $sexe;

        return $this;
    }

    public function getNationaliteEleve(): ?string
    {
        return $this->nationaliteEleve;
    }

    public function setNationaliteEleve(?string $nationaliteEleve): self
    {
        $this->nationaliteEleve = $nationaliteEleve;

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
