<?php

namespace App\Entity;

use App\Repository\HardSkillTypeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: HardSkillTypeRepository::class)]
class HardSkillType
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

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
