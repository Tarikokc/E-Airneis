<?php

namespace App\Entity;

use App\Repository\ProduitsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\UX\Turbo\Attribute\Broadcast;

#[ORM\Entity(repositoryClass: ProduitsRepository::class)]
#[ORM\Table(name: "products")]
#[Broadcast]
class Produits
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: "product_id")]
    #[Groups("product:read")]
    private ?int $productId = null;

    #[ORM\Column(length: 255)]
    #[Groups("product:read")]
    private ?string $Nom = null;

    #[ORM\Column(type: "text", nullable: true)]
    #[Groups("product:read")]
    private ?string $Description = null;

    #[ORM\Column]
    #[Groups("product:read")]
    private ?float $prix = null;

    #[ORM\Column(name: "stock_quantity")]
    #[Groups("product:read")]
    private ?int $Stock = null;

    #[ORM\ManyToOne(targetEntity: ProductCategories::class, inversedBy: 'products')]
    #[ORM\JoinColumn(name: "category_id", referencedColumnName: "category_id", nullable: false)]
    private ?ProductCategories $category = null;

    #[ORM\ManyToOne(targetEntity: Designers::class, inversedBy: 'products')]
    #[ORM\JoinColumn(name: "designer_id", referencedColumnName: "designer_id", nullable: true)]
    private ?Designers $designer = null;

    #[ORM\OneToMany(mappedBy: 'product', targetEntity: ProductPhotos::class, cascade: ["persist"])]
    #[Groups("product:read")]
    private Collection $productPhotos;

    public function __construct()
    {
        $this->productPhotos = new ArrayCollection();
    }

    // Getters and Setters (corrigés et complets)
    public function getProductId(): ?int
    {
        return $this->productId;
    }

    public function getNom(): ?string
    {
        return $this->Nom;
    }

    public function setNom(string $Nom): self
    {
        $this->Nom = $Nom;
        return $this; // Renvoie l'objet lui-même pour le chaînage de méthodes
    }

    public function getDescription(): ?string
    {
        return $this->Description;
    }

    public function setDescription(?string $Description): self
    {
        $this->Description = $Description;
        return $this; // Renvoie l'objet lui-même pour le chaînage de méthodes
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): self
    {
        $this->prix = $prix;
        return $this; // Renvoie l'objet lui-même pour le chaînage de méthodes
    }

    public function getStock(): ?int
    {
        return $this->Stock;
    }

    public function setStock(int $Stock): self
    {
        $this->Stock = $Stock;
        return $this; // Renvoie l'objet lui-même pour le chaînage de méthodes
    }

    public function getCategory(): ?ProductCategories
    {
        return $this->category;
    }

    public function setCategory(?ProductCategories $category): self
    {
        $this->category = $category;
        return $this; // Renvoie l'objet lui-même pour le chaînage de méthodes
    }

    public function getDesigner(): ?Designers
    {
        return $this->designer;
    }

    public function setDesigner(?Designers $designer): self
    {
        $this->designer = $designer;
        return $this; // Renvoie l'objet lui-même pour le chaînage de méthodes
    }

    /**
     * @return Collection<int, ProductPhotos>
     */
    public function getProductPhotos(): Collection
    {
        return $this->productPhotos;
    }

    public function addProductPhoto(ProductPhotos $productPhoto): self
    {
        if (!$this->productPhotos->contains($productPhoto)) {
            $this->productPhotos->add($productPhoto);
            $productPhoto->setProduct($this);
        }

        return $this;
    }

    public function removeProductPhoto(ProductPhotos $productPhoto): self
    {
        if ($this->productPhotos->removeElement($productPhoto)) {
            // set the owning side to null (unless already changed)
            if ($productPhoto->getProduct() === $this) {
                $productPhoto->setProduct(null);
            }
        }

        return $this;
    }
}
