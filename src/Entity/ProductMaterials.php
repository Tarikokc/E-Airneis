<?php
namespace App\Entity;

use App\Repository\ProductMaterialsRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @OA\Schema(
 *     description="Représente la relation entre un produit et un matériau."
 * )
 */
#[ORM\Entity(repositoryClass: ProductMaterialsRepository::class)]
#[ORM\Table(name: 'product_materials')] 
class ProductMaterials
{
     /**
     * @OA\Property(description="Produit associé au matériau")
     * @Groups({"materiaux:read", "product:read"})
     */
    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: Produits::class, inversedBy: 'productMaterials')]
    #[ORM\JoinColumn(name: 'product_id', referencedColumnName: 'product_id', onDelete: 'CASCADE')] 
    private Produits $produit; 

    /**
     * @OA\Property(description="Matériau associé au produit")
     * @Groups({"materiaux:read", "product:read"})
     */
    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: Materiaux::class, inversedBy: 'productMaterials')]
    #[ORM\JoinColumn(name: 'material_id', referencedColumnName: 'material_id', onDelete: 'CASCADE')]
    private Materiaux $materiaux; 

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
