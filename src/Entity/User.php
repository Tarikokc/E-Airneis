<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\UX\Turbo\Attribute\Broadcast;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @OA\Schema(
 *     description="Modèle de données pour un utilisateur",
 *     title="User"
 * )
 */
#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`users`')]
#[Broadcast]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @OA\Property(description="ID de l'utilisateur")
     */
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "AUTO")]
    #[ORM\Column(type: 'integer', name: 'user_id')]
    private ?int $id = null;
    /**
     * @OA\Property(description="Adresse e-mail de l'utilisateur")
     */

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $email = null;

    /**
     * @OA\Property(description="Mot de passe hashé de l'utilisateur")
     */
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $password = null;

    /**
     * @OA\Property(description="Prénom de l'utilisateur")
     */
    #[ORM\Column(name: 'first_name', length: 50, nullable: true)]
    private ?string $firstName = null;

    /**
     * @OA\Property(description="Nom de famille de l'utilisateur")
     */
    #[ORM\Column(name: 'last_name', length: 50, nullable: true)]
    private ?string $lastName = null;

    /**
     * @OA\Property(description="Adresse de l'utilisateur")
     */
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $address = null;

    /**
     * @OA\Property(description="Ville de l'utilisateur")
     */
    #[ORM\Column(length: 100, nullable: true)]
    private ?string $city = null;
    /**
     * @OA\Property(description="Pays de l'utilisateur")
     */
    #[ORM\Column(length: 100, nullable: true)]
    private ?string $country = null;
    /**
     * @OA\Property(description="Numéro de téléphone de l'utilisateur")
     */
    #[ORM\Column(name: 'phone_number', length: 20, nullable: true)]
    private ?string $phoneNumber = null;
    /**
     * @OA\Property(description="Date d'enregistrement de l'utilisateur")
     */
    #[ORM\Column(name: 'registration_date', nullable: true)]
    private ?\DateTimeInterface $registrationDate = null;
    /**
     * @OA\Property(description="Rôle de l'utilisateur")
     */
    #[ORM\Column(type: 'boolean')]
    private bool $role;
    /**
     * @OA\Property(description="Token de l'utilisateur")
     */
    #[ORM\Column(length: 255, nullable: true)] // Ajout du champ token
    private ?string $token = null;
    /**
     * @OA\Property(description="Le moyen de paiement de l'utilisateur")
     */
    #[ORM\Column(length: 50, nullable: true)]
    private ?string $paymentMethod = null;


    #[Groups(["panier", "order_details_with_products"])]
    public function getUserId(): ?int
    {
        return $this->id;
    }

    #[Groups('order_details_with_products')]
    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password): self
    {
        $this->password = $password;

        return $this;
    }

    #[Groups(["panier", "order_details_with_products"])]

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }


    public function setFirstName(?string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }
    #[Groups(["panier", "order_details_with_products"])]

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(?string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    #[Groups(["panier", "order_details_with_products"])]

    public function getAddress(): ?string
    {
        return $this->address;
    }
    #[Groups(["panier", "order_details_with_products"])]
    public function setAddress(?string $address): self
    {
        $this->address = $address;

        return $this;
    }
    #[Groups(["panier", "order_details_with_products"])]

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(?string $city): self
    {
        $this->city = $city;

        return $this;
    }

    #[Groups(["panier", "order_details_with_products"])]

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(?string $country): self
    {
        $this->country = $country;

        return $this;
    }
    #[Groups(["panier", "order_details_with_products"])] 

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(?string $phoneNumber): self
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    public function getRegistrationDate(): ?\DateTimeInterface
    {
        return $this->registrationDate;
    }

    public function setRegistrationDate(?\DateTimeInterface $registrationDate): self
    {
        $this->registrationDate = $registrationDate;

        return $this;
    }

    public function getRole(): bool
    {
        return $this->role;
    }

    public function setRole(bool $role): self
    {
        $this->role = $role;

        return $this;
    }

    // Implementation of UserInterface methods

    public function getRoles(): array
    {
        // Assuming role 1 represents 'ROLE_ADMIN' and role 0 represents 'ROLE_USER'
        return $this->role ? ['ROLE_ADMIN'] : ['ROLE_USER'];
    }

    public function getSalt(): ?string
    {
        return null; // Not needed for modern algorithms
    }

    public function eraseCredentials(): void
    {
        // Clear any temporary, sensitive data if any
    }

    public function getUsername(): ?string
    {
        return $this->email;
    }

    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(?string $token): self
    {
        $this->token = $token;
        return $this;
    }
    #[Groups(["panier", "order_details_with_products"])] 
    public function getPaymentMethod(): ?string
    {
        return $this->paymentMethod;
    }

    public function setPaymentMethod(?string $paymentMethod): self
    {
        $this->paymentMethod = $paymentMethod;
        return $this;
    }
}
