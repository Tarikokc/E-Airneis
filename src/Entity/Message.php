<?php

namespace App\Entity;

use App\Repository\MessageRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @OA\Schema(
 *     description="Représente un message de contact."
 * )
 */
#[ORM\Entity(repositoryClass: MessageRepository::class)]
class Message
{
    /**
     * @OA\Property(description="ID du message", readOnly=true)
     * @Groups({"message:read"})
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;
    /**
     * @OA\Property(description="Nom de l'expéditeur")
     * @Groups({"message:read"})
     */
    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le nom est obligatoire.")]
    private ?string $name = null;

    /**
     * @OA\Property(description="Adresse e-mail de l'expéditeur", format="email")
     * @Groups({"message:read"})
     */
    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "L'email est obligatoire.")]
    #[Assert\Email(message: "L'adresse e-mail n'est pas valide.")]
    private ?string $email = null;

    /**
     * @OA\Property(description="Sujet du message")
     * @Groups({"message:read"})
     */
    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le sujet est obligatoire.")]
    private ?string $subject = null;

    /**
     * @OA\Property(description="Corps du message")
     * @Groups({"message:read"})
     */
    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank(message: "Le message est obligatoire.")]
    private ?string $message = null;

    /**
     * @OA\Property(description="Date et heure de création du message", readOnly=true)
     * @Groups({"message:read"})
     */
    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getSubject(): ?string
    {
        return $this->subject;
    }

    public function setSubject(string $subject): self
    {
        $this->subject = $subject;

        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): self
    {
        $this->message = $message;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }
}
