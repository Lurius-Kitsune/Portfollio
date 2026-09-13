<?php

namespace App\Entity;

use App\Repository\ProjectContentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Translatable\Translatable;


#[ORM\Entity(repositoryClass: ProjectContentRepository::class)]
#[Gedmo\TranslationEntity(class: ProjectContentTranslation::class)]
class ProjectContent  implements Translatable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'projectContents')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Project $projectId = null;

    /**
     * @var Collection<int, ProjectMedia>
     */
    #[ORM\ManyToMany(targetEntity: ProjectMedia::class)]
    private Collection $linkMedia;

    #[Gedmo\Translatable]
    #[ORM\Column(length: 255)]
    private ?string $themeName = null;

    #[Gedmo\Translatable]
    #[ORM\Column(length: 255)]
    private ?string $title = null;

    /** @var string[] */
    #[Gedmo\Translatable]
    #[ORM\Column(type: Types::JSON, nullable: true)]
    private ?array $content = null;

    public function __construct()
    {
        $this->linkMedia = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    /**
     * @return Collection<int, ProjectMedia>
     */
    public function getLinkMedia(): Collection
    {
        return $this->linkMedia;
    }

    public function addLinkMedium(ProjectMedia $linkMedium): static
    {
        if (!$this->linkMedia->contains($linkMedium)) {
            $this->linkMedia->add($linkMedium);
        }

        return $this;
    }

    public function removeLinkMedium(ProjectMedia $linkMedium): static
    {
        $this->linkMedia->removeElement($linkMedium);

        return $this;
    }


    public function getThemeName(): ?string
    {
        return $this->themeName;
    }

    public function setThemeName(string $themeName): static
    {
        $this->themeName = $themeName;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getContent(): ?array
    {
        return $this->content;
    }

    public function setContent(?array $content): static
    {
        $this->content = $content;

        return $this;
    }
}
