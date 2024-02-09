<?php

namespace App\Entity;

use App\Repository\ShapeRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ShapeRepository::class)]
class Shape
{
    #[ORM\Column(type: 'integer')]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    private ?int $idEtat = null;

    #[ORM\Column(length: 255)]
    private ?string $wording = null;

    public function getIdEtat(): ?int
    {
        return $this->idEtat;
    }

    public function getWording(): ?string
    {
        return $this->wording;
    }

    public function setWording(string $wording): static
    {
        $this->wording = $wording;

        return $this;
    }
}
