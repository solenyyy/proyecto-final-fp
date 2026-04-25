<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Patch;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\CoordinatorRepository;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

#[ORM\Entity(repositoryClass: CoordinatorRepository::class)]
#[ApiResource(
    operations: [
        new Get(normalizationContext: ['groups' => ['coordinator:read']]),
        new GetCollection(normalizationContext: ['groups' => ['coordinator:read']]),
        new Post(normalizationContext: ['groups' => ['coordinator:read']], denormalizationContext: ['groups' => ['coordinator:write']]),
        new Patch(normalizationContext: ['groups' => ['coordinator:read']], denormalizationContext: ['groups' => ['coordinator:write']])
    ]
)]
class Coordinator implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['coordinator:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['coordinator:read', 'coordinator:write'])]
    private ?string $name = null;

    #[ORM\Column(length: 255, unique: true)]
    #[Groups(['coordinator:read', 'coordinator:write'])]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    private ?string $password = null;

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

    public function setEmail(?string $email): self
    {
        $this->email = $email;
        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;
        return $this;
    }

    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    public function getRoles(): array
    {
        return ['ROLE_COORDINATOR'];
    }

    public function eraseCredentials(): void {}
}
