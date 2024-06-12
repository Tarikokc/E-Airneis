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
    #[ORM\Column(name: "order_id")]
    private ?int $orderId = null;

    #[ORM\Column(name: "order_date", type: "date")]
    private ?\DateTimeInterface $orderDate = null;

    #[ORM\Column(name: "total_amount", type: "decimal", precision: 10, scale: 2)]
    private ?float $totalAmount = null; 

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: "orders")]
    #[ORM\JoinColumn(name: "user_id", referencedColumnName: "user_id", nullable: false)]
    private ?User $user = null; 

    #[ORM\OneToMany(mappedBy: "order", targetEntity: OrderDetail::class, cascade: ["persist"])]
    private Collection $orderDetails;

    public function __construct()
    {
        $this->orderDetails = new ArrayCollection();
    }

    // Getters
    public function getOrderId(): ?int { return $this->orderId; }
    public function getOrderDate(): ?\DateTimeInterface { return $this->orderDate; }
    public function getTotalAmount(): ?float { return $this->totalAmount; }
    public function getUser(): ?User { return $this->user; }
    public function getOrderDetails(): Collection { return $this->orderDetails; }

    // Setters
    public function setOrderDate(?\DateTimeInterface $orderDate): self
    {
        $this->orderDate = $orderDate;
        return $this; // Permet le chainage de méthodes (fluent interface)
    }

    public function setTotalAmount(?float $totalAmount): self
    {
        $this->totalAmount = $totalAmount;
        return $this;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;
        return $this;
    }
}
