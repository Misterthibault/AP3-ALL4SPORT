<?php

namespace App\Entity;

use App\Repository\CommandeRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommandeRepository::class)]
class Commande
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?\DateTime $date_commande = null;

    #[ORM\Column]
    private ?float $cout_total = null;

    #[ORM\Column(length: 255)]
    private ?string $lieu_expedition = null;

    #[ORM\Column(length: 255)]
    private ?string $addresse_livraison = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateCommande(): ?\DateTime
    {
        return $this->date_commande;
    }

    public function setDateCommande(\DateTime $date_commande): static
    {
        $this->date_commande = $date_commande;

        return $this;
    }

    public function getCoutTotal(): ?float
    {
        return $this->cout_total;
    }

    public function setCoutTotal(float $cout_total): static
    {
        $this->cout_total = $cout_total;

        return $this;
    }

    public function getLieuExpedition(): ?string
    {
        return $this->lieu_expedition;
    }

    public function setLieuExpedition(string $lieu_expedition): static
    {
        $this->lieu_expedition = $lieu_expedition;

        return $this;
    }

    public function getAddresseLivraison(): ?string
    {
        return $this->addresse_livraison;
    }

    public function setAddresseLivraison(string $addresse_livraison): static
    {
        $this->addresse_livraison = $addresse_livraison;

        return $this;
    }
}
