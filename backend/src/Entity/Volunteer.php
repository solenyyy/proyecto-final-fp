<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Patch;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity]
#[ORM\Table(name: "volunteers")]
#[ApiResource(
    operations: [
        new Get(normalizationContext: ['groups' => ['volunteer:read']]),
        new GetCollection(normalizationContext: ['groups' => ['volunteer:read']]),
        new Post(normalizationContext: ['groups' => ['volunteer:read']], denormalizationContext: ['groups' => ['volunteer:write']]),
        new Patch(normalizationContext: ['groups' => ['volunteer:read']], denormalizationContext: ['groups' => ['volunteer:write']])
    ]
)]
class Volunteer
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['volunteer:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['volunteer:read', 'volunteer:write', 'activity:read'])]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['volunteer:read', 'volunteer:write'])]
    private ?string $email = null;

    #[ORM\Column(length: 20)]
    #[Assert\NotBlank(message: "El DNI o NIE es obligatorio")]
    #[Groups(['volunteer:read', 'volunteer:write'])]
    private ?string $dniNie = null;

    #[ORM\Column(type: 'text', nullable: true)]
    #[Groups(['volunteer:read', 'volunteer:write'])]
    private ?string $bio = null;

    #[ORM\Column(type: 'date')]
    #[Assert\NotNull(message: "La fecha de nacimiento es obligatoria")]
    #[Groups(['volunteer:read', 'volunteer:write'])]
    private ?\DateTimeInterface $birthDate = null;

    #[ORM\OneToMany(mappedBy: 'volunteer', targetEntity: Activity::class, cascade: ['persist', 'remove'])]
    private Collection $activities;

    public function __construct()
    {
        $this->activities = new ArrayCollection();
    }

    public function getActivities(): Collection
    {
        return $this->activities;
    }

    public function addActivity(Activity $activity): self
    {
        if (!$this->activities->contains($activity)) {
            $this->activities->add($activity);
            $activity->setVolunteer($this);
        }
        return $this;
    }

    public function removeActivity(Activity $activity): self
    {
        if ($this->activities->removeElement($activity)) {
            if ($activity->getVolunteer() === $this) {
                $activity->setVolunteer(null);
            }
        }
        return $this;
    }

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

    public function getDniNie(): ?string
    {
        return $this->dniNie;
    }

    public function setDniNie(string $dniNie): self
    {
        $this->dniNie = $dniNie;
        return $this;
    }

    public function getBio(): ?string
    {
        return $this->bio;
    }

    public function setBio(?string $bio): self
    {
        $this->bio = $bio;
        return $this;
    }

    public function getBirthDate(): ?\DateTimeInterface
    {
        return $this->birthDate;
    }

    public function setBirthDate(\DateTimeInterface $birthDate): self
    {
        $this->birthDate = $birthDate;
        return $this;
    }
}
