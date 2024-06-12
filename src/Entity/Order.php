<?php

namespace App\Entity;

use App\Repository\OrderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrderRepository::class)]
#[ORM\Table(name: "orders")] 
class Order
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: "order_id")] // Assurez-vous que le nom de la colonne correspond à celui de la base de données
    private ?int $orderId = null;

    #[ORM\Column(name: "order_date", type: "date")] // Nom de la colonne corrigé
    private ?\DateTimeInterface $orderDate = null;

    #[ORM\Column(name: "total_amount", type: "decimal", precision: 10, scale: 2)] // Nom de la colonne corrigé
    private ?float $totalAmount = null; // Utilisez float pour les montants décimaux

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: "orders")]
    #[ORM\JoinColumn(name: "user_id", referencedColumnName: "user_id", nullable: false)]
    private ?User $user = null; // Changement du type en User (entité)

    #[ORM\OneToMany(mappedBy: "order", targetEntity: OrderDetail::class, cascade: ["persist"])] // Correction de "mappedBy"
    private Collection $orderDetails;

    public function __construct()
    {
        $this->orderDetails = new ArrayCollection();
    }

    // ... (Getters et setters pour toutes les propriétés)
}
