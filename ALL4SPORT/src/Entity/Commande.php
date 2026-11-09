<?php

namespace App\Entity;

use App\Repository\CommandeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: true, onDelete: 'SET NULL')]
    private ?Client $client = null;

    /**
     * @var Collection<int, LigneCommande>
     */
    #[ORM\OneToMany(mappedBy: 'commande', targetEntity: LigneCommande::class, cascade: ['persist'], orphanRemoval: true)]
    private Collection $lignes;

    /**
     * @var Collection<int, Produit>
     */
    #[ORM\ManyToMany(targetEntity: Produit::class, inversedBy: 'commandes')]
    private Collection $produit_id;

    public function __construct()
    {
        $this->lignes = new ArrayCollection();
        $this->produit_id = new ArrayCollection();
    }

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

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(?Client $client): static
    {
        $this->client = $client;

        return $this;
    }

    /**
     * @return Collection<int, LigneCommande>
     */
    public function getLignes(): Collection
    {
        return $this->lignes;
    }

    public function addLigne(LigneCommande $ligne): static
    {
        if (!$this->lignes->contains($ligne)) {
            $this->lignes->add($ligne);
            $ligne->setCommande($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Produit>
     */
    public function getProduitId(): Collection
    {
        return $this->produit_id;
    }

    public function addProduitId(Produit $produitId): static
    {
        if (!$this->produit_id->contains($produitId)) {
            $this->produit_id->add($produitId);
        }

        return $this;
    }

    public function removeProduitId(Produit $produitId): static
    {
        $this->produit_id->removeElement($produitId);

        return $this;
    }
}
