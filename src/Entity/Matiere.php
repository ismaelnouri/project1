<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Matiere
 *
 * @ORM\Table(name="matiere", indexes={@ORM\Index(name="id_type_matiere", columns={"id_type_matiere"}), @ORM\Index(name="id_niveau", columns={"id_niveau"})})
 * @ORM\Entity
 */
class Matiere
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_matiere", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idMatiere;

    /**
     * @var string|null
     *
     * @ORM\Column(name="code_matiere", type="string", length=255, nullable=true)
     */
    private $codeMatiere;

    /**
     * @var string
     *
     * @ORM\Column(name="libelle_matiere", type="string", length=255, nullable=false)
     */
    private $libelleMatiere;

    /**
     * @var int
     *
     * @ORM\Column(name="coefficient_matiere", type="integer", nullable=false)
     */
    private $coefficientMatiere;

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

    /**
     * @var \TypeMatiere
     *
     * @ORM\ManyToOne(targetEntity="TypeMatiere")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_type_matiere", referencedColumnName="id_type_matiere")
     * })
     */
    private $idTypeMatiere;

    public function getIdMatiere(): ?int
    {
        return $this->idMatiere;
    }

    public function getCodeMatiere(): ?string
    {
        return $this->codeMatiere;
    }

    public function setCodeMatiere(?string $codeMatiere): self
    {
        $this->codeMatiere = $codeMatiere;

        return $this;
    }

    public function getLibelleMatiere(): ?string
    {
        return $this->libelleMatiere;
    }

    public function setLibelleMatiere(string $libelleMatiere): self
    {
        $this->libelleMatiere = $libelleMatiere;

        return $this;
    }

    public function getCoefficientMatiere(): ?int
    {
        return $this->coefficientMatiere;
    }

    public function setCoefficientMatiere(int $coefficientMatiere): self
    {
        $this->coefficientMatiere = $coefficientMatiere;

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

    public function getIdTypeMatiere(): ?TypeMatiere
    {
        return $this->idTypeMatiere;
    }

    public function setIdTypeMatiere(?TypeMatiere $idTypeMatiere): self
    {
        $this->idTypeMatiere = $idTypeMatiere;

        return $this;
    }


}
