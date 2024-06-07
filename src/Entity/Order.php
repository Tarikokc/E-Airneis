<?php

namespace App\Entity;

use App\Repository\OrderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrderRepository::class)]
#[ORM\Table(name: "orders")] // Nom de la table dans la base de données
class Order
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $orderId = null; // Utilisation de orderId pour correspondre à la BD

    #[ORM\Column(type: "date")]
    private ?\DateTimeInterface $orderDate = null;

    #[ORM\Column(type: "decimal", precision: 10, scale: 2)]
    private ?string $totalAmount = null;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: "orders")]
    #[ORM\JoinColumn(name: "user_id", referencedColumnName: "user_id")] // Relation avec la table users
    private ?User $userId = null;

    #[ORM\OneToMany(mappedBy: "orderId", targetEntity: OrderDetail::class)]
    private Collection $orderDetails; // Relation avec les détails de commande

    public function __construct()
    {
        $this->orderDetails = new ArrayCollection();
    }

    // Getters and setters (ajoutez les méthodes pour les autres propriétés)
    // ...
}
