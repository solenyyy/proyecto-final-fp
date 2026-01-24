<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Patch;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity]
#[ApiResource(
    operations: [
        new Get(normalizationContext: ['groups' => ['coordinator:read']]),
        new GetCollection(normalizationContext: ['groups' => ['coordinator:read']]),
        new Post(normalizationContext: ['groups' => ['coordinator:read']], denormalizationContext: ['groups' => ['coordinator:write']]),
        new Patch(normalizationContext: ['groups' => ['coordinator:read']], denormalizationContext: ['groups' => ['coordinator:write']])
    ]
)]
class Coordinator
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['coordinator:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['coordinator:read', 'coordinator:write'])]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['coordinator:read', 'coordinator:write'])]
    private ?string $email = null;

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
}
