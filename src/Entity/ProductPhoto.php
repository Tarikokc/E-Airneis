<?php

namespace App\Entity;

use App\Repository\ProductPhotoRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\UX\Turbo\Attribute\Broadcast;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ProductPhotoRepository::class)]
#[ORM\Table(name: "productphotos")] // Utilisez "products" au lieu de "produits"
#[Broadcast]
class ProductPhoto
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: "photo_id")]
    private ?int $id = null;

    #[ORM\Column(name: "photo_url", length: 255)]
    #[Groups("product:read")]
    private ?string $photoUrl = null;

    #[ORM\Column(name: "is_primary")]
    private ?bool $isPrimary = null;

    #[ORM\ManyToOne(inversedBy: 'productPhotos')]
    #[ORM\JoinColumn(name: "product_id", referencedColumnName: "product_id")]
    private ?Produits $product = null;
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPhotoUrl(): ?string
    {
        return $this->photoUrl;
    }

    public function setPhotoUrl(string $photoUrl): self
    {
        $this->photoUrl = $photoUrl;

        return $this;
    }

    public function isIsPrimary(): ?bool
    {
        return $this->isPrimary;
    }

    public function setIsPrimary(bool $isPrimary): self
    {
        $this->isPrimary = $isPrimary;

        return $this;
    }

    public function getProduct(): ?Produits
    {
        return $this->product;
    }

    public function setProduct(?Produits $product): self
    {
        $this->product = $product;

        return $this;
    }
}
