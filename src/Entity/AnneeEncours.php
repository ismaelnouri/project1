<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AnneeEncours
 *
 * @ORM\Table(name="annee_encours", indexes={@ORM\Index(name="id_semestre", columns={"id_semestre"}), @ORM\Index(name="id_annee", columns={"id_annee"})})
 * @ORM\Entity
 */
class AnneeEncours
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_annee_encours", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idAnneeEncours;

    /**
     * @var \Annee
     *
     * @ORM\ManyToOne(targetEntity="Annee")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_annee", referencedColumnName="id_annee")
     * })
     */
    private $idAnnee;

    /**
     * @var \Semestre
     *
     * @ORM\ManyToOne(targetEntity="Semestre")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_semestre", referencedColumnName="id_semestre")
     * })
     */
    private $idSemestre;

    /**
     * @var int
     *
     * @ORM\Column(name="statut", type="integer", nullable=false)
     */
    private $statut;

    public function getIdAnneeEncours(): ?int
    {
        return $this->idAnneeEncours;
    }

    public function getIdAnnee(): ?Annee
    {
        return $this->idAnnee;
    }

    public function setIdAnnee(?Annee $idAnnee): self
    {
        $this->idAnnee = $idAnnee;

        return $this;
    }

    public function getIdSemestre(): ?Semestre
    {
        return $this->idSemestre;
    }

    public function setIdSemestre(?Semestre $idSemestre): self
    {
        $this->idSemestre = $idSemestre;

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
