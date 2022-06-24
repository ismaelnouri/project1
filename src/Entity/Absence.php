<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Absence
 *
 * @ORM\Table(name="absence", indexes={@ORM\Index(name="id_eleve", columns={"id_eleve"}), @ORM\Index(name="id_utilisateur", columns={"id_utilisateur"}), @ORM\Index(name="id_annee", columns={"id_annee"}), @ORM\Index(name="id_semestre", columns={"id_semestre"}), @ORM\Index(name="id_heure", columns={"id_heure"})})
 * @ORM\Entity
 */
class Absence
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_absence", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idAbsence;

    /**
     * @var string
     *
     * @ORM\Column(name="commentaire", type="text", length=65535, nullable=false)
     */
    private $commentaire;

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
     * @var \Utilisateur
     *
     * @ORM\ManyToOne(targetEntity="Utilisateur")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_utilisateur", referencedColumnName="id_utilisateur")
     * })
     */
    private $idUtilisateur;

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
     * @var \Heure
     *
     * @ORM\ManyToOne(targetEntity="Heure")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_heure", referencedColumnName="id_heure")
     * })
     */
    private $idHeure;

    /**
     * @var \Eleve
     *
     * @ORM\ManyToOne(targetEntity="Eleve")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_eleve", referencedColumnName="id_eleve")
     * })
     */
    private $idEleve;

    public function getIdAbsence(): ?int
    {
        return $this->idAbsence;
    }

    public function getCommentaire(): ?string
    {
        return $this->commentaire;
    }

    public function setCommentaire(string $commentaire): self
    {
        $this->commentaire = $commentaire;

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

    public function getIdUtilisateur(): ?Utilisateur
    {
        return $this->idUtilisateur;
    }

    public function setIdUtilisateur(?Utilisateur $idUtilisateur): self
    {
        $this->idUtilisateur = $idUtilisateur;

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

    public function getIdSemestre(): ?Semestre
    {
        return $this->idSemestre;
    }

    public function setIdSemestre(?Semestre $idSemestre): self
    {
        $this->idSemestre = $idSemestre;

        return $this;
    }

    public function getIdHeure(): ?Heure
    {
        return $this->idHeure;
    }

    public function setIdHeure(?Heure $idHeure): self
    {
        $this->idHeure = $idHeure;

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


}
