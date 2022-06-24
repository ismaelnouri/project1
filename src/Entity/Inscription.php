<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Inscription
 *
 * @ORM\Table(name="inscription", indexes={@ORM\Index(name="id_utilisateur", columns={"id_utilisateur"}), @ORM\Index(name="id_classe", columns={"id_classe"}), @ORM\Index(name="id_annee", columns={"id_annee"}), @ORM\Index(name="id_eleve", columns={"id_eleve"})})
 * @ORM\Entity
 */
class Inscription
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_inscription", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idInscription;

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
     * @var \DateTime|null
     *
     * @ORM\Column(name="date_paiement", type="datetime", nullable=true)
     */
    private $datePaiement;

    /**
     * @var string|null
     *
     * @ORM\Column(name="versement_effectuer", type="decimal", precision=10, scale=0, nullable=true)
     */
    private $versementEffectuer;

    /**
     * @var string|null
     *
     * @ORM\Column(name="versement_restant", type="decimal", precision=10, scale=0, nullable=true)
     */
    private $versementRestant;

    /**
     * @var int|null
     *
     * @ORM\Column(name="coges", type="integer", nullable=true)
     */
    private $coges;

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
     * @var \Eleve
     *
     * @ORM\ManyToOne(targetEntity="Eleve")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_eleve", referencedColumnName="id_eleve")
     * })
     */
    private $idEleve;

    /**
     * @var \Classe
     *
     * @ORM\ManyToOne(targetEntity="Classe")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_classe", referencedColumnName="id_classe")
     * })
     */
    private $idClasse;

    public function getIdInscription(): ?int
    {
        return $this->idInscription;
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

    public function getDatePaiement(): ?\DateTimeInterface
    {
        return $this->datePaiement;
    }

    public function setDatePaiement(?\DateTimeInterface $datePaiement): self
    {
        $this->datePaiement = $datePaiement;

        return $this;
    }

    public function getVersementEffectuer(): ?string
    {
        return $this->versementEffectuer;
    }

    public function setVersementEffectuer(?string $versementEffectuer): self
    {
        $this->versementEffectuer = $versementEffectuer;

        return $this;
    }

    public function getVersementRestant(): ?string
    {
        return $this->versementRestant;
    }

    public function setVersementRestant(?string $versementRestant): self
    {
        $this->versementRestant = $versementRestant;

        return $this;
    }

    public function getCoges(): ?int
    {
        return $this->coges;
    }

    public function setCoges(?int $coges): self
    {
        $this->coges = $coges;

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

    public function getIdEleve(): ?Eleve
    {
        return $this->idEleve;
    }

    public function setIdEleve(?Eleve $idEleve): self
    {
        $this->idEleve = $idEleve;

        return $this;
    }

    public function getIdClasse(): ?Classe
    {
        return $this->idClasse;
    }

    public function setIdClasse(?Classe $idClasse): self
    {
        $this->idClasse = $idClasse;

        return $this;
    }


}
