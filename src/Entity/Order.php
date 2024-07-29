<?php

namespace App\Entity;

use App\Repository\OrderRepository;
use App\Entity\User;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\Common\Collections\Collection; // Ajoutez cette ligne
use Doctrine\Common\Collections\ArrayCollection;

#[ORM\Entity(repositoryClass: OrderRepository::class)]
#[ORM\Table(name: '`orders`')]
class Order
{
    public function __construct()
    {
        $this->orderDetails = new ArrayCollection(); // Initialisation de la collection
    }

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $order_id = null;

    #[ORM\ManyToOne(inversedBy: 'order')]
    #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'user_id', nullable: false)]
    #[Groups('order_details_with_products')]
    private ?User $user = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $order_date = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2, nullable: true)]
    private ?float $total_amount = null;

    #[ORM\OneToMany(mappedBy: 'order', targetEntity: OrderDetail::class)]
    #[Groups('order_details_with_products')]
    private Collection $orderDetails;

    public function getOrderId(): ?int
    {
        return $this->order_id;
    }

    public function setOrderId(?int $order_id): self
    {
        $this->order_id = $order_id;
        return $this;
    }


    #[Groups('order_details_with_products')]
    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
    #[Groups('order_details_with_products')] // Ajouté
    public function getOrderDetails(): Collection
    {
        return $this->orderDetails;
    }

    public function getOrderDate(): ?\DateTimeInterface
    {
        return $this->order_date;
    }

    public function setOrderDate(?\DateTimeInterface $order_date): self
    {
        $this->order_date = $order_date;

        return $this;
    }

    public function getTotalAmount(): ?float
    {
        return $this->total_amount;
    }

    public function setTotalAmount(?float $total_amount): self
    {
        $this->total_amount = $total_amount;

        return $this;
    }
}
