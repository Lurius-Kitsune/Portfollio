<?php

namespace App\Entity;

use Gedmo\Mapping\Annotation as Gedmo;
use App\Repository\HardSkillTypeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Translatable\Translatable;

#[ORM\Entity(repositoryClass: HardSkillTypeRepository::class)]
#[Gedmo\TranslationEntity(class: HardSkillTypeTranslation::class)]
class HardSkillType implements Translatable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Gedmo\Translatable]
    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[Gedmo\Locale]
    private ?string $locale = null;

    public function setTranslatableLocale(?string $locale): void
    {
        $this->locale = $locale;
    }

    /**
     * @var Collection<int, HardSkill>
     */
    #[ORM\OneToMany(targetEntity: HardSkill::class, mappedBy: 'type', orphanRemoval: true)]
    private Collection $hardSkills;

    public function __construct()
    {
        $this->hardSkills = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    /**
     * @return Collection<int, HardSkill>
     */
    public function getHardSkills(): Collection
    {
        return $this->hardSkills;
    }

    public function addHardSkill(HardSkill $hardSkill): static
    {
        if (!$this->hardSkills->contains($hardSkill)) {
            $this->hardSkills->add($hardSkill);
            $hardSkill->setType($this);
        }

        return $this;
    }

    public function removeHardSkill(HardSkill $hardSkill): static
    {
        if ($this->hardSkills->removeElement($hardSkill)) {
            // set the owning side to null (unless already changed)
            if ($hardSkill->getType() === $this) {
                $hardSkill->setType(null);
            }
        }

        return $this;
    }
}
