<?php

namespace App\Entity;

use App\Repository\AchatRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AchatRepository::class)]
class Achat
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id_achat = null;

    #[ORM\Column(type: "date")]
    private ?\DateTimeInterface $dateAchat = null;

    #[ORM\Column(type: "decimal", precision: 10, scale: 2)]
    private ?float $montant = null;

    #[ORM\Column(type: "string", length: 100)]
    private ?string $fournisseur = null;

    #[ORM\Column(type: "integer")]
    private ?int $idStock = null;

    // Getter and Setter for $id_achat
    public function getId(): ?int
    {
        return $this->id_achat;
    }

    // Getter and Setter for $dateAchat
    public function getDateAchat(): ?\DateTimeInterface
    {
        return $this->dateAchat;
    }

    public function setDateAchat(\DateTimeInterface $dateAchat): self
    {
        $this->dateAchat = $dateAchat;
        return $this;
    }

    // Getter and Setter for $montant
    public function getMontant(): ?float
    {
        return $this->montant;
    }

    public function setMontant(float $montant): self
    {
        $this->montant = $montant;
        return $this;
    }

    // Getter and Setter for $fournisseur
    public function getFournisseur(): ?string
    {
        return $this->fournisseur;
    }

    public function setFournisseur(string $fournisseur): self
    {
        $this->fournisseur = $fournisseur;
        return $this;
    }

    public function getIdStock(): ?int
    {
        return $this->idStock;
    }

    // Setter pour idStock
    public function setIdStock(int $idStock): self
    {
        $this->idStock = $idStock;

        return $this;
    }
    
}
