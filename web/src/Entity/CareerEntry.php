<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Translatable\Translatable;

#[ORM\Entity]
#[ORM\Table(name: 'career_entry')]
#[ORM\Index(name: 'career_entry_dates_idx', columns: ['start_date'])]
#[Gedmo\TranslationEntity(class: CareerEntryTranslation::class)]
class CareerEntry implements Translatable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 20)]
    private string $category = 'education';

    #[ORM\Column(length: 255)]
    #[Gedmo\Translatable]
    private string $title = '';

    #[ORM\Column(length: 255)]
    #[Gedmo\Translatable]
    private string $organization = '';

    #[ORM\Column(type: Types::DATE_IMMUTABLE)]
    private \DateTimeImmutable $startDate;

    public function __construct()
    {
        $this->startDate = new \DateTimeImmutable();
    }

    #[ORM\Column(type: Types::DATE_IMMUTABLE, nullable: true)]
    private ?\DateTimeImmutable $endDate = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Gedmo\Translatable]
    private ?string $description = null;

    #[ORM\Column]
    private int $position = 0;

    public function getId(): ?int { return $this->id; }
    public function getCategory(): string { return $this->category; }
    public function getTitle(): string { return $this->title; }
    public function getOrganization(): string { return $this->organization; }
    public function getStartDate(): \DateTimeImmutable { return $this->startDate; }
    public function getEndDate(): ?\DateTimeImmutable { return $this->endDate; }
    public function getDescription(): ?string { return $this->description; }
    public function getPosition(): int { return $this->position; }
    public function setCategory(string $category): static { $this->category = $category; return $this; }
    public function setTitle(string $title): static { $this->title = $title; return $this; }
    public function setOrganization(string $organization): static { $this->organization = $organization; return $this; }
    public function setStartDate(\DateTimeImmutable $startDate): static { $this->startDate = $startDate; return $this; }
    public function setEndDate(?\DateTimeImmutable $endDate): static { $this->endDate = $endDate; return $this; }
    public function setDescription(?string $description): static { $this->description = $description; return $this; }
    public function setPosition(int $position): static { $this->position = $position; return $this; }
}
