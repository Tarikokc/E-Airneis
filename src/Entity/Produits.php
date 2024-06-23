<?php

namespace App\Entity;

use App\Repository\ProduitsRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\UX\Turbo\Attribute\Broadcast;
use Doctrine\Common\Collections\Collection; // Ajoutez cette ligne
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\Common\Collections\ArrayCollection;

#[ORM\Entity(repositoryClass: ProduitsRepository::class)]
#[ORM\Table(name: "products")] // Utilisez "products" au lieu de "produits"
#[Broadcast]
class Produits
{
    public function __construct()
    {
        $this->productPhotos = new ArrayCollection();
        $this->materiaux = new ArrayCollection(); // 

    }
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[Groups("product:read")]
    #[ORM\Column(name: "product_id")] // Indiquez que product_id est l'ID


    private ?int $productId = null; // Renommez en $productId

    #[Groups("product:read")]
    #[ORM\Column(name: "name", length: 255)]
    private ?string $Nom = null;

    #[Groups("product:read")]
    #[ORM\Column(length: 255)]
    private ?string $Description = null;

    #[Groups("product:read")]
    #[ORM\Column(name: "price")]
    private ?int $prix = null;

    #[Groups("product:read")]
    #[ORM\Column(name: "stock_quantity")]
    private ?int $Stock = null;

    #[ORM\OneToMany(mappedBy: 'product', targetEntity: ProductPhoto::class)]
    #[Groups("product:read")] 
    private Collection $productPhotos;

    #[ORM\ManyToOne(targetEntity: Categories::class, inversedBy: 'produits')]
    #[ORM\JoinColumn(name: "category_id", referencedColumnName: "category_id")]
    private ?Categories $category = null;

   

    #[ORM\ManyToMany(targetEntity: Materiaux::class, inversedBy: "produits", fetch: "EAGER")]
    #[ORM\JoinTable(
        name: "product_materials",
        joinColumns: [new ORM\JoinColumn(name: "product_id", referencedColumnName: "product_id")],
        inverseJoinColumns: [new ORM\JoinColumn(name: "material_id", referencedColumnName: "material_id")]
    )]
    #[Groups("product:read")]
    private Collection $materiaux;

    public function getProductId(): ?int
    {
        return $this->productId;
    }

    public function getNom(): ?string
    {
        return $this->Nom;
    }

    public function setNom(string $Nom): static
    {
        $this->Nom = $Nom;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->Description;
    }

    public function setDescription(string $Description): static
    {
        $this->Description = $Description;

        return $this;
    }

    public function getPrix(): ?int
    {
        return $this->prix;
    }

    public function setPrix(int $prix): static
    {
        $this->prix = $prix;

        return $this;
    }

    public function getStock(): ?int
    {
        return $this->Stock;
    }

    public function setStock(int $Stock): static
    {
        $this->Stock = $Stock;

        return $this;
    }
    /**
     * @return Collection<int, ProductPhoto>
     */
    public function getProductPhotos(): Collection
    {
        return $this->productPhotos;
    }

    public function addProductPhoto(ProductPhoto $productPhoto): self
    {
        if (!$this->productPhotos->contains($productPhoto)) {
            $this->productPhotos->add($productPhoto);
            $productPhoto->setProduct($this);
        }

        return $this;
    }

    public function removeProductPhoto(ProductPhoto $productPhoto): self
    {
        if ($this->productPhotos->removeElement($productPhoto)) {
            if ($productPhoto->getProduct() === $this) {
                $productPhoto->setProduct(null);
            }
        }

        return $this;
    }

    public function getCategory(): ?Categories
    {
        return $this->category;
    }

    public function setCategory(?Categories $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getMateriaux(): Collection
    {
        return $this->materiaux;
    }

    public function addMateriaux(Materiaux $materiaux): self
    {
        if (!$this->materiaux->contains($materiaux)) {
            $this->materiaux->add($materiaux);
        }

        return $this;
    }

    public function removeMateriaux(Materiaux $materiaux): self
    {
        $this->materiaux->removeElement($materiaux);

        return $this;
    }
}