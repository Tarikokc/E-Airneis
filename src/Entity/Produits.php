<?php

namespace App\Entity;

use App\Repository\ProduitsRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\UX\Turbo\Attribute\Broadcast;
use Doctrine\Common\Collections\Collection; // Ajoutez cette ligne
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ProduitsRepository::class)]
#[ORM\Table(name: "products")] // Utilisez "products" au lieu de "produits"
#[Broadcast]
class Produits
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: "product_id")] // Indiquez que product_id est l'ID


    private ?int $productId = null; // Renommez en $productId

    #[ORM\Column(name: "name", length: 255)]
    private ?string $Nom = null;

    #[ORM\Column(length: 255)]
    private ?string $Description = null;

    #[ORM\Column(name: "price")]
    private ?int $prix = null;

    #[ORM\Column(name: "stock_quantity")]
    private ?int $Stock = null;

    // #[ORM\OneToMany(mappedBy: 'product', targetEntity: ProductPhoto::class)]
    // #[Groups("product:read")]
    // private Collection $productPhotos;

    #[ORM\OneToMany(mappedBy: 'product', targetEntity: ProductPhoto::class, fetch: "EAGER")]
    #[Groups("product:read")]
    private Collection $productPhotos;
    public function getId(): ?int
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
    public function getProductPhotos(): Collection
    {
        return $this->productPhotos;
    }
}
