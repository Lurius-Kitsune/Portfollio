<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Translatable\Entity\MappedSuperclass\AbstractTranslation;
use Gedmo\Translatable\Entity\Repository\TranslationRepository;

#[ORM\Entity(repositoryClass: TranslationRepository::class)]
#[ORM\Table(name: 'project_translations')]
class ProjectTranslation extends AbstractTranslation {}
