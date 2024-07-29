<?php

namespace App\Entity;

use App\Repository\OrderDetailRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @OA\Schema(
 *     description="Représente les détails d'une ligne de commande."
 * )
 */
#[ORM\Entity(repositoryClass: OrderDetailRepository::class)]
#[ORM\Table(name: "orderdetails")]
class OrderDetail
{
     /**
     * @OA\Property(description="ID du détail de la commande")
     * @Groups({"order:read"})
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: "order_detail_id")] 
    private ?int $orderDetailId = null; 

    /**
     * @OA\Property(description="Commande associée")
     * @Groups({"order:read"})
     */
    #[ORM\ManyToOne(targetEntity: Order::class, inversedBy: "orderDetails")]
    #[ORM\JoinColumn(name: "order_id", referencedColumnName: "order_id", nullable: false)]
    private ?Order $order = null; 

    /**
     * @OA\Property(description="Produit associé")
     * @Groups({"order:read"})
     */
    #[ORM\ManyToOne(targetEntity: Produits::class)]
    #[ORM\JoinColumn(name: "product_id", referencedColumnName: "product_id", nullable: false)]
    private ?Produits $product = null;

    /**
     * @OA\Property(description="Quantité commandée")
     * @Groups({"order:read"})
     */
    #[ORM\Column(name: "quantity")] 
    private ?int $quantity = null;

    /**
     * @OA\Property(description="Prix unitaire du produit", type="number", format="float")
     * @Groups({"order:read"})
     */
    #[ORM\Column(name: "unit_price", type: "decimal", precision: 10, scale: 2)] 
    private ?float $unitPrice = null; 

    public function getOrderDetailId(): ?int
    {
        return $this->orderDetailId;
    }
    #[Groups('order_details')]    
    public function getOrder(): ?Order
    {
        return $this->order;
    }

    public function setOrder(?Order $order): self
    {
        $this->order = $order;

        return $this;
    }

    #[Groups('order_details_with_products')]
    public function getProduct(): ?Produits
    {
        return $this->product;
    }

    public function setProduct(?Produits $product): self
    {
        $this->product = $product;

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getUnitPrice(): ?float
    {
        return $this->unitPrice;
    }

    public function setUnitPrice(float $unitPrice): self
    {
        $this->unitPrice = $unitPrice;

        return $this;
    }
}
