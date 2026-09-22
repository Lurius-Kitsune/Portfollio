<?php

namespace App\Entity;

use App\Enum\EMediaType;
use App\Repository\ProjectMediaRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProjectMediaRepository::class)]
class ProjectMedia
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'projectMedia')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Project $projectId = null;

    #[ORM\Column(enumType: EMediaType::class)]
    private ?EMediaType $type = null;

    #[ORM\Column(length: 300)]
    private ?string $url = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $name = null;



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?EMediaType
    {
        return $this->type;
    }

    public function setType(EMediaType $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): static
    {
        $this->url = $url;

        return $this;
    }

    public function getProjectId(): ?Project
    {
        return $this->projectId;
    }

    public function setProjectId(?Project $projectId): static
    {
        $this->projectId = $projectId;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }
}
