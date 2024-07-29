<?php

namespace App\Entity;

use App\Repository\DesignerRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use OpenApi\Annotations as OA;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @OA\Schema(
 *     description="Représente un designer de meubles."
 * )
 */
#[ORM\Entity(repositoryClass: DesignerRepository::class)]
#[ORM\Table(name: "designers")] 
class Designer
{

    
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    /**
     * @OA\Property(description="ID du designer")
     * @Groups({"product:read"})
     */
    private ?int $designerId = null;

    #[ORM\Column(length: 100, nullable: true)]
     /**
     * @OA\Property(description="Nom du designer")
     * @Groups({"product:read"})
     */
    private ?string $designerName = null;

    #[ORM\Column(type: 'text', nullable: true)]
    /**
     * @OA\Property(description="Description du designer")
     * @Groups({"product:read"})
     */
    private ?string $description = null;

    #[ORM\OneToMany(mappedBy: 'designer', targetEntity: Produits::class)]
    /**
     * @var Collection<int, Produits>
     * @ORM\OneToMany(mappedBy="designer", targetEntity=Produits::class)
     */
    private Collection $products;

    public function __construct()
    {
        $this->products = new ArrayCollection();
    }

    public function getDesignerId(): ?int
    {
        return $this->designerId;
    }

    public function getDesignerName(): ?string
    {
        return $this->designerName;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getProducts(): Collection
    {
        return $this->products;
    }

    // Setters
    public function setDesignerName(?string $designerName): self
    {
        $this->designerName = $designerName;

        return $this;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }
}
