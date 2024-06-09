<?php

namespace App\Entity;

use App\Repository\OrderDetailRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrderDetailRepository::class)]
#[ORM\Table(name: "orderdetails")] // Nom de la table dans la base de données
class OrderDetail
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $orderDetailId = null; // Utilisation de orderDetailId pour correspondre à la BD

    #[ORM\ManyToOne(targetEntity: Order::class, inversedBy: "orderDetails")]
    #[ORM\JoinColumn(name: "order_id", referencedColumnName: "order_id")] // Relation avec la table orders
    private ?Order $orderId = null;

    #[ORM\ManyToOne(targetEntity: Product::class)] // Supposons que vous avez une entité Product
    #[ORM\JoinColumn(name: "product_id", referencedColumnName: "product_id")] // Relation avec la table products
    private ?Product $productId = null;

    #[ORM\Column]
    private ?int $quantity = null;

    #[ORM\Column(type: "decimal", precision: 10, scale: 2)]
    private ?string $unitPrice = null;

    // Getters and setters (ajoutez les méthodes pour les autres propriétés)
    // ...
}
