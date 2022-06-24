<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * VersementCoges
 *
 * @ORM\Table(name="versement_coges", indexes={@ORM\Index(name="id_inscription", columns={"id_inscription"})})
 * @ORM\Entity
 */
class VersementCoges
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_versement_coges", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idVersementCoges;

    /**
     * @var string
     *
     * @ORM\Column(name="montant", type="decimal", precision=10, scale=0, nullable=false)
     */
    private $montant;

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
     * @var \Inscription
     *
     * @ORM\ManyToOne(targetEntity="Inscription")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_inscription", referencedColumnName="id_inscription")
     * })
     */
    private $idInscription;

    public function getIdVersementCoges(): ?int
    {
        return $this->idVersementCoges;
    }

    public function getMontant(): ?string
    {
        return $this->montant;
    }

    public function setMontant(string $montant): self
    {
        $this->montant = $montant;

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

    public function getIdInscription(): ?Inscription
    {
        return $this->idInscription;
    }

    public function setIdInscription(?Inscription $idInscription): self
    {
        $this->idInscription = $idInscription;

        return $this;
    }


}
