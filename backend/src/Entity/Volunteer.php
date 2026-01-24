<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Patch;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity]
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
    #[Groups(['volunteer:read', 'volunteer:write'])]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['volunteer:read', 'volunteer:write'])]
    private ?string $email = null;

    #[ORM\OneToMany(mappedBy: 'volunteer', targetEntity: Activity::class, cascade: ['persist', 'remove'])]
    private Collection $activities;

public function __construct()
{
    $this->activities = new ArrayCollection();
}

/**
 * @return Collection<int, Activity>
 */
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
}
