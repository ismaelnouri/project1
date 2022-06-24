<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * NoteSemestrielle
 *
 * @ORM\Table(name="note_semestrielle", indexes={@ORM\Index(name="id_semestre", columns={"id_semestre"}), @ORM\Index(name="id_eleve", columns={"id_eleve"}), @ORM\Index(name="id_matiere", columns={"id_matiere"}), @ORM\Index(name="note_semestrielle_ibfk_4", columns={"id_prof"}), @ORM\Index(name="id_annee", columns={"id_annee"})})
 * @ORM\Entity
 */
class NoteSemestrielle
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_note_semestrielle", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idNoteSemestrielle;

    /**
     * @var int
     *
     * @ORM\Column(name="coefficient", type="integer", nullable=false)
     */
    private $coefficient;

    /**
     * @var float
     *
     * @ORM\Column(name="note_classe", type="float", precision=10, scale=0, nullable=false)
     */
    private $noteClasse;

    /**
     * @var float
     *
     * @ORM\Column(name="note_compo", type="float", precision=10, scale=0, nullable=false)
     */
    private $noteCompo;

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
     *   @ORM\JoinColumn(name="id_prof", referencedColumnName="id_utilisateur")
     * })
     */
    private $idProf;

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
     * @var \Matiere
     *
     * @ORM\ManyToOne(targetEntity="Matiere")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_matiere", referencedColumnName="id_matiere")
     * })
     */
    private $idMatiere;

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
     * @var \Eleve
     *
     * @ORM\ManyToOne(targetEntity="Eleve")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_eleve", referencedColumnName="id_eleve")
     * })
     */
    private $idEleve;

    public function getIdNoteSemestrielle(): ?int
    {
        return $this->idNoteSemestrielle;
    }

    public function getCoefficient(): ?int
    {
        return $this->coefficient;
    }

    public function setCoefficient(int $coefficient): self
    {
        $this->coefficient = $coefficient;

        return $this;
    }

    public function getNoteClasse(): ?float
    {
        return $this->noteClasse;
    }

    public function setNoteClasse(float $noteClasse): self
    {
        $this->noteClasse = $noteClasse;

        return $this;
    }

    public function getNoteCompo(): ?float
    {
        return $this->noteCompo;
    }

    public function setNoteCompo(float $noteCompo): self
    {
        $this->noteCompo = $noteCompo;

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

    public function getIdProf(): ?Utilisateur
    {
        return $this->idProf;
    }

    public function setIdProf(?Utilisateur $idProf): self
    {
        $this->idProf = $idProf;

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

    public function getIdMatiere(): ?Matiere
    {
        return $this->idMatiere;
    }

    public function setIdMatiere(?Matiere $idMatiere): self
    {
        $this->idMatiere = $idMatiere;

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
