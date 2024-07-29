<?php

namespace App\Entity;

use App\Repository\PanierRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\UX\Turbo\Attribute\Broadcast;
use Symfony\Component\Serializer\Annotation\Groups;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema()
 */
#[ORM\Entity(repositoryClass: PanierRepository::class)]
#[Broadcast]
class Panier
{
    
    /**
     * @OA\Property(description="ID du panier")
     * @Groups({"panier:read"})
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * @OA\Property(description="Utilisateur associé au panier")
     * @Groups({"panier:read"})
     */
    #[ORM\ManyToOne(targetEntity: User::class)] 
    #[ORM\JoinColumn(name: "user_id", referencedColumnName: "user_id")]
    #[Groups("panier")] // Ajoutez le groupe de sérialisation
    private ?User $user = null;

    /**
     * @OA\Property(description="Produit dans le panier")
     * @Groups({"panier:read"})
     */
    #[ORM\ManyToOne(targetEntity: Produits::class)] 
    #[ORM\JoinColumn(name: "product_id", referencedColumnName: "product_id")]
    #[Groups("panier")] // Ajoutez le groupe de sérialisation 
    private ?Produits $produit = null;

    /**
     * @OA\Property(description="Quantité du produit dans le panier")
     * @Groups({"panier:read"})
     */
    #[ORM\Column]
    private ?int $quantite = null;

    /**
     * @OA\Property(description="Prix du produit dans le panier")
     * @Groups({"panier:read"})
     */
    #[ORM\Column]
    private ?float $prix = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }


    #[Groups("panier")] // Ajoutez le groupe de sérialisation
    public function getProduit(): ?Produits
    {
        return $this->produit;
    }
    #[Groups("panier")] // Ajoutez le groupe de sérialisation
    public function setProduit(?Produits $produit): static
    {
        $this->produit = $produit;

        return $this;
    }

    public function getQuantite(): ?int
    {
        return $this->quantite;
    }

    public function setQuantite(int $quantite): static
    {
        $this->quantite = $quantite;

        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): static
    {
        $this->prix = $prix;

        return $this;
    }
}
