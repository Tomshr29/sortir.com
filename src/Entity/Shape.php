<?php

namespace App\Entity;

use App\Repository\ShapeRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ShapeRepository::class)]
class Shape
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $idEtat = null;

    #[ORM\Column(length: 255)]
    private ?string $wording = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdEtat(): ?int
    {
        return $this->idEtat;
    }

    public function setIdEtat(int $idEtat): static
    {
        $this->idEtat = $idEtat;

        return $this;
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
