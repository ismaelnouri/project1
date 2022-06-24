<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Transfert
 *
 * @ORM\Table(name="transfert", indexes={@ORM\Index(name="transfert_ibfk_2", columns={"id_niveau"}), @ORM\Index(name="id_eleve", columns={"id_eleve"}), @ORM\Index(name="id_annee", columns={"id_annee"}), @ORM\Index(name="id_utilisateur", columns={"id_utilisateur"})})
 * @ORM\Entity
 */
class Transfert
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_transfert", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idTransfert;

    /**
     * @var string|null
     *
     * @ORM\Column(name="reference_transfert", type="string", length=255, nullable=true)
     */
    private $referenceTransfert;

    /**
     * @var string|null
     *
     * @ORM\Column(name="source", type="string", length=255, nullable=true)
     */
    private $source;

    /**
     * @var string|null
     *
     * @ORM\Column(name="destination", type="string", length=255, nullable=true)
     */
    private $destination;

    /**
     * @var string|null
     *
     * @ORM\Column(name="redouble", type="string", length=255, nullable=true)
     */
    private $redouble;

    /**
     * @var int|null
     *
     * @ORM\Column(name="inscrit", type="integer", nullable=true)
     */
    private $inscrit;

    /**
     * @var int
     *
     * @ORM\Column(name="type_transfert", type="integer", nullable=false)
     */
    private $typeTransfert;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="date_transfert", type="date", nullable=true)
     */
    private $dateTransfert;

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
     * @var \Eleve
     *
     * @ORM\ManyToOne(targetEntity="Eleve")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_eleve", referencedColumnName="id_eleve")
     * })
     */
    private $idEleve;

    /**
     * @var \Niveau
     *
     * @ORM\ManyToOne(targetEntity="Niveau")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_niveau", referencedColumnName="id_niveau")
     * })
     */
    private $idNiveau;

    public function getIdTransfert(): ?int
    {
        return $this->idTransfert;
    }

    public function getReferenceTransfert(): ?string
    {
        return $this->referenceTransfert;
    }

    public function setReferenceTransfert(?string $referenceTransfert): self
    {
        $this->referenceTransfert = $referenceTransfert;

        return $this;
    }

    public function getSource(): ?string
    {
        return $this->source;
    }

    public function setSource(?string $source): self
    {
        $this->source = $source;

        return $this;
    }

    public function getDestination(): ?string
    {
        return $this->destination;
    }

    public function setDestination(?string $destination): self
    {
        $this->destination = $destination;

        return $this;
    }

    public function getRedouble(): ?string
    {
        return $this->redouble;
    }

    public function setRedouble(?string $redouble): self
    {
        $this->redouble = $redouble;

        return $this;
    }

    public function getInscrit(): ?int
    {
        return $this->inscrit;
    }

    public function setInscrit(?int $inscrit): self
    {
        $this->inscrit = $inscrit;

        return $this;
    }

    public function getTypeTransfert(): ?int
    {
        return $this->typeTransfert;
    }

    public function setTypeTransfert(int $typeTransfert): self
    {
        $this->typeTransfert = $typeTransfert;

        return $this;
    }

    public function getDateTransfert(): ?\DateTimeInterface
    {
        return $this->dateTransfert;
    }

    public function setDateTransfert(?\DateTimeInterface $dateTransfert): self
    {
        $this->dateTransfert = $dateTransfert;

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

    public function getIdEleve(): ?Eleve
    {
        return $this->idEleve;
    }

    public function setIdEleve(?Eleve $idEleve): self
    {
        $this->idEleve = $idEleve;

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
