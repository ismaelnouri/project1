<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * MatiereProf
 *
 * @ORM\Table(name="matiere_prof", indexes={@ORM\Index(name="id_annee", columns={"id_annee"}), @ORM\Index(name="id_utilisateur", columns={"id_utilisateur"}), @ORM\Index(name="matiere_prof_ibfk_3", columns={"id_type_matiere"})})
 * @ORM\Entity
 */
class MatiereProf
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_matiere_prof", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idMatiereProf;

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
     * @var \TypeMatiere
     *
     * @ORM\ManyToOne(targetEntity="TypeMatiere")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_type_matiere", referencedColumnName="id_type_matiere")
     * })
     */
    private $idTypeMatiere;

    /**
     * @var \Annee
     *
     * @ORM\ManyToOne(targetEntity="Annee")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_annee", referencedColumnName="id_annee")
     * })
     */
    private $idAnnee;

    public function getIdMatiereProf(): ?int
    {
        return $this->idMatiereProf;
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

    public function getIdTypeMatiere(): ?TypeMatiere
    {
        return $this->idTypeMatiere;
    }

    public function setIdTypeMatiere(?TypeMatiere $idTypeMatiere): self
    {
        $this->idTypeMatiere = $idTypeMatiere;

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


}
