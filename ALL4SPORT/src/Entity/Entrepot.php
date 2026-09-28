<?php

namespace App\Entity;

use App\Repository\EntrepotRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EntrepotRepository::class)]
class Entrepot
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * @var Collection<int, produit>
     */
    #[ORM\ManyToMany(targetEntity: produit::class, inversedBy: 'entrepots')]
    private Collection $fk_produit;

    public function __construct()
    {
        $this->fk_produit = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, produit>
     */
    public function getFkProduit(): Collection
    {
        return $this->fk_produit;
    }

    public function addFkProduit(produit $fkProduit): static
    {
        if (!$this->fk_produit->contains($fkProduit)) {
            $this->fk_produit->add($fkProduit);
        }

        return $this;
    }

    public function removeFkProduit(produit $fkProduit): static
    {
        $this->fk_produit->removeElement($fkProduit);

        return $this;
    }
}
