<?php

namespace App\Entity;

use App\Repository\ProjectRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Translatable\Translatable;

#[ORM\Entity(repositoryClass: ProjectRepository::class)]
#[Gedmo\TranslationEntity(class: ProjectTranslation::class)]
class Project implements Translatable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255, unique: true)]
    private ?string $slug = null;

    #[ORM\ManyToOne(inversedBy: 'projects')]
    #[ORM\JoinColumn(nullable: false)]
    private ?ProjectType $type = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE)]
    private ?\DateTimeImmutable $start_year = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE, nullable: true)]
    private ?\DateTimeImmutable $end_date = null;

    #[ORM\Column(length: 1000, nullable: true)]
    private ?string $thumbnail_url = null;

    #[ORM\Column(length: 300, nullable: true)]
    private ?string $link = null;

    #[ORM\Column(type: Types::SIMPLE_ARRAY, nullable: true)]
    private ?array $tags = null;

    /**
     * @var Collection<int, ProjectMedia>
     */
    #[ORM\OneToMany(targetEntity: ProjectMedia::class, mappedBy: 'projectId')]
    private Collection $projectMedia;

    /**
     * @var Collection<int, ProjectContent>
     */
    #[ORM\OneToMany(targetEntity: ProjectContent::class, mappedBy: 'projectId', orphanRemoval: true)]
    private Collection $projectContents;

    #[Gedmo\Translatable]
    #[ORM\Column(length: 255)]
    private ?string $role = null;

    #[Gedmo\Translatable]
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $intro = null;

    #[Gedmo\Translatable]
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $conclusionTitle = null;

    #[Gedmo\Translatable]
    #[ORM\Column(nullable: true)]
    private ?array $conclusionContent = null;

    public function __construct()
    {
        $this->projectMedia = new ArrayCollection();
        $this->projectContents = new ArrayCollection();
    }




    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

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

    public function getType(): ?ProjectType
    {
        return $this->type;
    }

    public function setType(?ProjectType $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getStartYear(): ?\DateTimeImmutable
    {
        return $this->start_year;
    }

    public function setStartYear(\DateTimeImmutable $start_year): static
    {
        $this->start_year = $start_year;

        return $this;
    }

    public function getEndDate(): ?\DateTimeImmutable
    {
        return $this->end_date;
    }

    public function setEndDate(?\DateTimeImmutable $end_date): static
    {
        $this->end_date = $end_date;

        return $this;
    }

    public function getThumbnailUrl(): ?string
    {
        return $this->thumbnail_url;
    }

    public function setThumbnailUrl(?string $thumbnail_url): static
    {
        $this->thumbnail_url = $thumbnail_url;

        return $this;
    }

    public function getLink(): ?string
    {
        return $this->link;
    }

    public function setLink(?string $link): static
    {
        $this->link = $link;

        return $this;
    }

    public function getTags(): ?array
    {
        return $this->tags;
    }

    public function setTags(?array $tags): static
    {
        $this->tags = $tags;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * @return Collection<int, ProjectMedia>
     */
    public function getProjectMedia(): Collection
    {
        return $this->projectMedia;
    }

    public function addProjectMedium(ProjectMedia $projectMedium): static
    {
        if (!$this->projectMedia->contains($projectMedium)) {
            $this->projectMedia->add($projectMedium);
            $projectMedium->setProjectId($this);
        }

        return $this;
    }

    public function removeProjectMedium(ProjectMedia $projectMedium): static
    {
        if ($this->projectMedia->removeElement($projectMedium)) {
            // set the owning side to null (unless already changed)
            if ($projectMedium->getProjectId() === $this) {
                $projectMedium->setProjectId(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, ProjectContent>
     */
    public function getProjectContents(): Collection
    {
        return $this->projectContents;
    }

    public function addProjectContent(ProjectContent $projectContent): static
    {
        if (!$this->projectContents->contains($projectContent)) {
            $this->projectContents->add($projectContent);
            $projectContent->setProjectId($this);
        }

        return $this;
    }

    public function removeProjectContent(ProjectContent $projectContent): static
    {
        if ($this->projectContents->removeElement($projectContent)) {
            // set the owning side to null (unless already changed)
            if ($projectContent->getProjectId() === $this) {
                $projectContent->setProjectId(null);
            }
        }

        return $this;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(string $role): static
    {
        $this->role = $role;

        return $this;
    }

    public function getIntro(): ?string
    {
        return $this->intro;
    }

    public function setIntro(?string $intro): static
    {
        $this->intro = $intro;

        return $this;
    }

    public function getConclusionTitle(): ?string
    {
        return $this->conclusionTitle;
    }

    public function setConclusionTitle(?string $conclusionTitle): static
    {
        $this->conclusionTitle = $conclusionTitle;

        return $this;
    }

    public function getConclusionContent(): ?array
    {
        return $this->conclusionContent;
    }

    public function setConclusionContent(?array $conclusionContent): static
    {
        $this->conclusionContent = $conclusionContent;

        return $this;
    }
}
