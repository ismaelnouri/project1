<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Classe
 *
 * @ORM\Table(name="classe", indexes={@ORM\Index(name="id_niveau", columns={"id_niveau"})})
 * @ORM\Entity(repositoryClass="App\Repository\VerifRepository")
 */
class Classe
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_classe", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idClasse;

    /**
     * @var string
     *
     * @ORM\Column(name="code_classe", type="string", length=255, nullable=false)
     */
    private $codeClasse;

    /**
     * @var string
     *
     * @ORM\Column(name="libelle_classe", type="string", length=255, nullable=false)
     */
    private $libelleClasse;

    /**
     * @var int|null
     *
     * @ORM\Column(name="nbre_place", type="integer", nullable=true)
     */
    private $nbrePlace;

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

    /**
     * @var \Niveau
     *
     * @ORM\ManyToOne(targetEntity="Niveau")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_niveau", referencedColumnName="id_niveau")
     * })
     */
    private $idNiveau;

    public function getIdClasse(): ?int
    {
        return $this->idClasse;
    }

    public function getCodeClasse(): ?string
    {
        return $this->codeClasse;
    }

    public function setCodeClasse(string $codeClasse): self
    {
        $this->codeClasse = $codeClasse;

        return $this;
    }

    public function getLibelleClasse(): ?string
    {
        return $this->libelleClasse;
    }

    public function setLibelleClasse(string $libelleClasse): self
    {
        $this->libelleClasse = $libelleClasse;

        return $this;
    }

    public function getNbrePlace(): ?int
    {
        return $this->nbrePlace;
    }

    public function setNbrePlace(?int $nbrePlace): self
    {
        $this->nbrePlace = $nbrePlace;

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

    public function getIdNiveau(): ?Niveau
    {
        return $this->idNiveau;
    }

    public function setIdNiveau(?Niveau $idNiveau): self
    {
        $this->idNiveau = $idNiveau;

        return $this;
    }


}
