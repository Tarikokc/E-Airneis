<?php
namespace App\Entity;

use App\Repository\ProductMaterialsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProductMaterialsRepository::class)]
#[ORM\Table(name: 'product_materials')] // Correction du nom de la table
class ProductMaterials
{
    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: Produits::class, inversedBy: 'productMaterials')]
    #[ORM\JoinColumn(name: 'product_id', referencedColumnName: 'product_id', onDelete: 'CASCADE')] 
    private Produits $produit; // Relation avec l'entité Produits

    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: Materiaux::class, inversedBy: 'productMaterials')]
    #[ORM\JoinColumn(name: 'material_id', referencedColumnName: 'material_id', onDelete: 'CASCADE')]
    private Materiaux $materiaux; // Relation avec l'entité Materiaux

    // Getters et setters pour $produit et $materiaux
    public function getProduit(): Produits
    {
        return $this->produit;
    }

    public function setProduit(Produits $produit): self
    {
        $this->produit = $produit;
        return $this;
    }

    public function getMateriaux(): Materiaux
    {
        return $this->materiaux;
    }

    public function setMateriaux(Materiaux $materiaux): self
    {
        $this->materiaux = $materiaux;
        return $this;
    }
}
