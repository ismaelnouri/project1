<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Exclure
 *
 * @ORM\Table(name="exclure", indexes={@ORM\Index(name="id_niveau", columns={"id_niveau"}), @ORM\Index(name="id_eleve", columns={"id_eleve"}), @ORM\Index(name="id_annee", columns={"id_annee"})})
 * @ORM\Entity
 */
class Exclure
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_exclure", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idExclure;

    /**
     * @var int
     *
     * @ORM\Column(name="date_ajout", type="integer", nullable=false)
     */
    private $dateAjout;

    /**
     * @var int|null
     *
     * @ORM\Column(name="date_sup", type="integer", nullable=true)
     */
    private $dateSup;

    /**
     * @var int
     *
     * @ORM\Column(name="statut", type="integer", nullable=false)
     */
    private $statut;

    /**
     * @var \Eleve
     *
     * @ORM\ManyToOne(targetEntity="Eleve")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_eleve", referencedColumnName="id_eleve")
     * })
     */
    private $idEleve;

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
     * @var \Niveau
     *
     * @ORM\ManyToOne(targetEntity="Niveau")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_niveau", referencedColumnName="id_niveau")
     * })
     */
    private $idNiveau;

    public function getIdExclure(): ?int
    {
        return $this->idExclure;
    }

    public function getDateAjout(): ?int
    {
        return $this->dateAjout;
    }

    public function setDateAjout(int $dateAjout): self
    {
        $this->dateAjout = $dateAjout;

        return $this;
    }

    public function getDateSup(): ?int
    {
        return $this->dateSup;
    }

    public function setDateSup(?int $dateSup): self
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

    public function getIdEleve(): ?Eleve
    {
        return $this->idEleve;
    }

    public function setIdEleve(?Eleve $idEleve): self
    {
        $this->idEleve = $idEleve;

        return $this;
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
