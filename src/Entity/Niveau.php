<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Niveau
 *
 * @ORM\Table(name="niveau", indexes={@ORM\Index(name="id_section", columns={"id_section"})})
 * @ORM\Entity
 */
class Niveau
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_niveau", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idNiveau;

    /**
     * @var string|null
     *
     * @ORM\Column(name="code_niveau", type="string", length=255, nullable=true)
     */
    private $codeNiveau;

    /**
     * @var string
     *
     * @ORM\Column(name="libelle_niveau", type="string", length=255, nullable=false)
     */
    private $libelleNiveau;

    /**
     * @var string
     *
     * @ORM\Column(name="frais_scolarite", type="decimal", precision=10, scale=0, nullable=false)
     */
    private $fraisScolarite;

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
     * @var \Section
     *
     * @ORM\ManyToOne(targetEntity="Section")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_section", referencedColumnName="id_section")
     * })
     */
    private $idSection;

    public function getIdNiveau(): ?int
    {
        return $this->idNiveau;
    }

    public function getCodeNiveau(): ?string
    {
        return $this->codeNiveau;
    }

    public function setCodeNiveau(?string $codeNiveau): self
    {
        $this->codeNiveau = $codeNiveau;

        return $this;
    }

    public function getLibelleNiveau(): ?string
    {
        return $this->libelleNiveau;
    }

    public function setLibelleNiveau(string $libelleNiveau): self
    {
        $this->libelleNiveau = $libelleNiveau;

        return $this;
    }

    public function getFraisScolarite(): ?string
    {
        return $this->fraisScolarite;
    }

    public function setFraisScolarite(string $fraisScolarite): self
    {
        $this->fraisScolarite = $fraisScolarite;

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

    public function getIdSection(): ?Section
    {
        return $this->idSection;
    }

    public function setIdSection(?Section $idSection): self
    {
        $this->idSection = $idSection;

        return $this;
    }


}
