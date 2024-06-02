<?php

// src/Entity/ProductPhoto.php
namespace App\Entity;

use App\Repository\ProductPhotoRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ProductPhotoRepository::class)]
#[ORM\Table(name: "productphotos")] // Utilisez "products" au lieu de "produits"
// #[Broadcast]

class ProductPhoto
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: "photo_id")] // Indiquez que product_id est l'ID
    

    private ?int $photoId = null;

    #[ORM\Column(name : "photo_url",length: 255)]
    #[Groups("product:read")] // Ajoutez cette annotation pour inclure l'URL de la photo dans la réponse JSON
    private ?string $photoUrl = null;

    #[ORM\ManyToOne(targetEntity: Produits::class, inversedBy: 'productPhotos')]
    #[ORM\JoinColumn(name: "product_id", referencedColumnName: "product_id", nullable: false)]
    private ?Produits $product = null;

    

    public function getId(): ?int
    {
        return $this->photoId;
    }

    public function getPhotoUrl(): ?string
    {
        return $this->photoUrl;
    }

    public function setPhotoUrl(string $photoUrl): static
    {
        $this->photoUrl = $photoUrl;

        return $this;
    }

    public function getProduct(): ?Produits
    {
        return $this->product;
    }

    public function setProduct(?Produits $product): static
    {
        $this->product = $product;

        return $this;
    }
}
