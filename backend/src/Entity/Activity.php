<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Doctrine\Orm\Filter\DateFilter;
use ApiPlatform\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Enum\Collective;

#[ORM\Table(name: "activities")]
#[ORM\Entity]
#[ApiResource(
    operations: [
        new Get(normalizationContext: ['groups' => ['activity:read']]),
        new GetCollection(normalizationContext: ['groups' => ['activity:read']]),
        new Post(normalizationContext: ['groups' => ['activity:read']], denormalizationContext: ['groups' => ['activity:write']]),
        new Patch(normalizationContext: ['groups' => ['activity:read']], denormalizationContext: ['groups' => ['activity:write']])
    ]
)]
#[ApiFilter(SearchFilter::class, properties: [
    'collective' => 'exact',
    'volunteer.id' => 'exact',
])]
#[ApiFilter(DateFilter::class, properties: [
    'startDate',
    'endDate'
])]
#[ApiFilter(OrderFilter::class, properties: [
    'startDate',
    'endDate'
], arguments: ['orderParameterName' => 'order'])]
class Activity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['activity:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['activity:read', 'activity:write'])]
    private ?string $name = null;

    #[ORM\Column(length: 500, nullable: true)]
    #[Groups(['activity:read', 'activity:write'])]
    private ?string $description = null;

    #[ORM\Column(type: 'datetime', nullable: false)]
    #[Assert\NotNull(message: "La fecha de inicio es obligatoria")]
    #[Groups(['activity:read', 'activity:write'])]
    private ?\DateTimeInterface $startDate = null;

    #[ORM\Column(type: 'datetime', nullable: false)]
    #[Assert\NotNull(message: "La fecha de fin es obligatoria")]
    #[Assert\GreaterThan(propertyPath: "startDate", message: "La fecha de fin debe ser posterior a la fecha de inicio")]
    #[Groups(['activity:read', 'activity:write'])]
    private ?\DateTimeInterface $endDate = null;

    #[ORM\Column(enumType: Collective::class)]
    #[Groups(['activity:read', 'activity:write'])]
    private ?Collective $collective = null;

    #[ORM\ManyToOne(targetEntity: Volunteer::class, inversedBy: 'activities')]
    #[ORM\JoinColumn(name: "volunteer_id", referencedColumnName: "id", nullable: true)]
    #[Groups(['activity:read', 'activity:write'])]
    private ?Volunteer $volunteer = null;

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;
        return $this;
    }

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->startDate;
    }

    public function setStartDate(\DateTimeInterface $startDate): self
    {
        $this->startDate = $startDate;
        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->endDate;
    }

    public function setEndDate(\DateTimeInterface $endDate): self
    {
        $this->endDate = $endDate;
        return $this;
    }

    public function getCollective(): ?Collective
    {
        return $this->collective;
    }

    public function setCollective(?Collective $collective): self
    {
        $this->collective = $collective;
        return $this;
    }

    public function getVolunteer(): ?Volunteer
    {
        return $this->volunteer;
    }

    public function setVolunteer(?Volunteer $volunteer): self
    {
        $this->volunteer = $volunteer;
        return $this;
    }
}
