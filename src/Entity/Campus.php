<?php

namespace App\Entity;

use App\Repository\CampusRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CampusRepository::class)]
class Campus
{
    #[ORM\Column(type: 'integer')]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    private ?int $idCampus = null;

    #[ORM\Column(type: 'string', length: 255)]
    private ?string $name = null;

    public function getIdCampus(): ?int
    {
        return $this->idCampus;
    }

    public function setIdCampus(int $idCampus): static
    {
        $this->idCampus = $idCampus;

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
