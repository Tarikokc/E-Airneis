<?php

// src/Entity/Materiaux.php
namespace App\Entity;

use App\Repository\MateriauxRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: MateriauxRepository::class)]
#[ORM\Table(name: "materials")] // Utilisez "products" au lieu de "produits"

class Materiaux
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: "material_id")] // Indiquez que product_id est l'ID
    #[Groups("materials:read")]
    private ?int $id = null;

    #[ORM\Column(name: "material_name")] // Indiquez que product_id est l'ID
    #[Groups("materials:read")]
    private ?string $nom = null;

    #[ORM\ManyToMany(targetEntity: Produits::class, mappedBy: 'materials')]
    private Collection $produits;

    public function __construct()
    {
        $this->produits = new ArrayCollection();
    }

    // Getters et setters

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * @return Collection<int, Produits>
     */
    public function getProduits(): Collection
    {
        return $this->produits;
    }

    // public function addProduit(Produits $produit): self
    // {
    //     if (!$this->produits->contains($produit)) {
    //         $this->produits->add($produit);
    //         $produit->addMateriaux($this);
    //     }

    //     return $this;
    // }

    // public function removeProduit(Produits $produit): self
    // {
    //     if ($this->produits->removeElement($produit)) {
    //         $produit->removeMateriaux($this);
    //     }

    //     return $this;
    // }
}
