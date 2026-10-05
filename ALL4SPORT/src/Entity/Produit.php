<?php

namespace App\Entity;

use App\Repository\ProduitRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProduitRepository::class)]
class Produit
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 18)]
    private ?string $ref = null;

    #[ORM\Column]
    private ?float $prix_vente = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $nom_fournisseur = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $description = null;

    /**
     * @var Collection<int, Magasin>
     */
    #[ORM\ManyToMany(targetEntity: Magasin::class, mappedBy: 'fk_produit')]
    private Collection $magasins;

    /**
     * @var Collection<int, Entrepot>
     */
    #[ORM\ManyToMany(targetEntity: Entrepot::class, mappedBy: 'fk_produit')]
    private Collection $entrepots;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    public function __construct()
    {
        $this->magasins = new ArrayCollection();
        $this->entrepots = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRef(): ?string
    {
        return $this->ref;
    }

    public function setRef(string $ref): static
    {
        $this->ref = $ref;

        return $this;
    }

    public function getPrixVente(): ?float
    {
        return $this->prix_vente;
    }

    public function setPrixVente(float $prix_vente): static
    {
        $this->prix_vente = $prix_vente;

        return $this;
    }

    public function getNomFournisseur(): ?string
    {
        return $this->nom_fournisseur;
    }

    public function setNomFournisseur(?string $nom_fournisseur): static
    {
        $this->nom_fournisseur = $nom_fournisseur;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection<int, Magasin>
     */
    public function getMagasins(): Collection
    {
        return $this->magasins;
    }

    public function addMagasin(Magasin $magasin): static
    {
        if (!$this->magasins->contains($magasin)) {
            $this->magasins->add($magasin);
            $magasin->addFkProduit($this);
        }

        return $this;
    }

    public function removeMagasin(Magasin $magasin): static
    {
        if ($this->magasins->removeElement($magasin)) {
            $magasin->removeFkProduit($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Entrepot>
     */
    public function getEntrepots(): Collection
    {
        return $this->entrepots;
    }

    public function addEntrepot(Entrepot $entrepot): static
    {
        if (!$this->entrepots->contains($entrepot)) {
            $this->entrepots->add($entrepot);
            $entrepot->addFkProduit($this);
        }

        return $this;
    }

    public function removeEntrepot(Entrepot $entrepot): static
    {
        if ($this->entrepots->removeElement($entrepot)) {
            $entrepot->removeFkProduit($this);
        }

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }
}
