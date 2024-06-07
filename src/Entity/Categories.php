<?php

namespace App\Entity;

use App\Repository\CategoriesRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\UX\Turbo\Attribute\Broadcast;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

#[ORM\Entity(repositoryClass: CategoriesRepository::class)]
#[ORM\Table(name: "productcategories")]
#[Broadcast]

class Categories
{

    #[ORM\OneToMany(mappedBy: 'category', targetEntity: Produits::class)]
    private Collection $produits;

    public function __construct()
    {
        $this->produits = new ArrayCollection();
    }
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: "category_id")]
    #[Groups("category:read")]
    private ?int $categoryId = null;

    #[ORM\Column(name: "category_name", length: 50)]
    #[Groups("category:read")]
    private ?string $categoryName = null;

    #[Groups("category:read")]
    public ?string $defaultPhotoUrl = null; // Propriété pour stocker l'URL de l'image par défaut
    public function getCategoryId(): ?int
    {
        return $this->categoryId;
    }

    public function getCategoryName(): ?string
    {
        return $this->categoryName;
    }

    public function setCategoryName(string $Nom): static
    {
        $this->categoryName = $Nom;

        return $this;
    }

    /**
     * @return Collection<int, Produits>
     */
    public function getProduits(): Collection
    {
        return $this->produits;
    }

    public function addProduit(Produits $produit): self
    {
        if (!$this->produits->contains($produit)) {
            $this->produits->add($produit);
            $produit->setCategory($this);
        }

        return $this;
    }

    public function removeProduit(Produits $produit): self
    {
        if ($this->produits->removeElement($produit)) {
            // set the owning side to null (unless already changed)
            if ($produit->getCategory() === $this) {
                $produit->setCategory(null);
            }
        }

        return $this;
    }
}
