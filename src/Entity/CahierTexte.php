<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CahierTexte
 *
 * @ORM\Table(name="cahier_texte", indexes={@ORM\Index(name="id_heure", columns={"id_heure"}), @ORM\Index(name="id_utilisateur", columns={"id_utilisateur"}), @ORM\Index(name="id_jour", columns={"id_jour"}), @ORM\Index(name="id_annee", columns={"id_annee"}), @ORM\Index(name="id_semestre", columns={"id_semestre"})})
 * @ORM\Entity
 */
class CahierTexte
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_cahier_texte", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idCahierTexte;

    /**
     * @var string
     *
     * @ORM\Column(name="contenu", type="text", length=65535, nullable=false)
     */
    private $contenu;

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
     * @ORM\Column(name="validation", type="integer", nullable=false)
     */
    private $validation;

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
     * @var \Jour
     *
     * @ORM\ManyToOne(targetEntity="Jour")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_jour", referencedColumnName="id_jour")
     * })
     */
    private $idJour;

    /**
     * @var \Heure
     *
     * @ORM\ManyToOne(targetEntity="Heure")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_heure", referencedColumnName="id_heure")
     * })
     */
    private $idHeure;

    public function getIdCahierTexte(): ?int
    {
        return $this->idCahierTexte;
    }

    public function getContenu(): ?string
    {
        return $this->contenu;
    }

    public function setContenu(string $contenu): self
    {
        $this->contenu = $contenu;

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

    public function getValidation(): ?int
    {
        return $this->validation;
    }

    public function setValidation(int $validation): self
    {
        $this->validation = $validation;

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

    public function getIdJour(): ?Jour
    {
        return $this->idJour;
    }

    public function setIdJour(?Jour $idJour): self
    {
        $this->idJour = $idJour;

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


}
