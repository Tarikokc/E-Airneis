<?php

namespace App\Entity;

use App\Repository\OrderDetailRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrderDetailRepository::class)]
#[ORM\Table(name: "orderdetails")]
class OrderDetail
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: "order_detail_id")] // Assurez-vous que le nom de la colonne correspond à celui de la base de données
    private ?int $orderDetailId = null; 

    #[ORM\ManyToOne(targetEntity: Order::class, inversedBy: "orderDetails")]
    #[ORM\JoinColumn(name: "order_id", referencedColumnName: "order_id", nullable: false)]
    private ?Order $order = null; // Changement du type en Order (entité)

    #[ORM\ManyToOne(targetEntity: Product::class)]
    #[ORM\JoinColumn(name: "product_id", referencedColumnName: "product_id", nullable: false)]
    private ?Product $product = null; // Changement du type en Product (entité)

    #[ORM\Column(name: "quantity")] // Assurez-vous que le nom de la colonne correspond à celui de la base de données
    private ?int $quantity = null;

    #[ORM\Column(name: "unit_price", type: "decimal", precision: 10, scale: 2)] // Assurez-vous que le nom de la colonne correspond à celui de la base de données
    private ?float $unitPrice = null; // Utilisation de float pour les prix

    // Getters and setters (ajoutez les méthodes pour les autres propriétés)
    public function getOrderDetailId(): ?int
    {
        return $this->orderDetailId;
    }

    public function getOrder(): ?Order
    {
        return $this->order;
    }

    public function setOrder(?Order $order): self
    {
        $this->order = $order;

        return $this;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): self
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
