<?php

namespace App\Entity;

use App\Repository\TripRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TripRepository::class)]
class Trip
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateTimeStart = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $duration = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $registrationDeadline = null;

    #[ORM\Column]
    private ?int $maxNumbRegistration = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $tripInfo = null;

    #[ORM\Column(length: 50)]
    private ?string $statut = null;

    #[ORM\ManyToOne(inversedBy: 'trip')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Place $place = null;

    #[ORM\ManyToOne(inversedBy: 'trip')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Shape $shape = null;

    #[ORM\ManyToOne(inversedBy: 'trip')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Campus $campus = null;

    #[ORM\ManyToOne(inversedBy: 'trip')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $organizer = null;

    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'trips')]
    private Collection $participant;

    public function __construct()
    {
        $this->participant = new ArrayCollection();
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

    public function getDateTimeStart(): ?\DateTimeInterface
    {
        return $this->dateTimeStart;
    }

    public function setDateTimeStart(\DateTimeInterface $dateTimeStart): static
    {
        $this->dateTimeStart = $dateTimeStart;

        return $this;
    }

    public function getDuration(): ?\DateTimeInterface
    {
        return $this->duration;
    }

    public function setDuration(\DateTimeInterface $duration): static
    {
        $this->duration = $duration;

        return $this;
    }

    public function getRegistrationDeadline(): ?\DateTimeInterface
    {
        return $this->registrationDeadline;
    }

    public function setRegistrationDeadline(\DateTimeInterface $registrationDeadline): static
    {
        $this->registrationDeadline = $registrationDeadline;

        return $this;
    }

    public function getMaxNumbRegistration(): ?int
    {
        return $this->maxNumbRegistration;
    }

    public function setMaxNumbRegistration(int $maxNumbRegistration): static
    {
        $this->maxNumbRegistration = $maxNumbRegistration;

        return $this;
    }

    public function getTripInfo(): ?string
    {
        return $this->tripInfo;
    }

    public function setTripInfo(?string $tripInfo): static
    {
        $this->tripInfo = $tripInfo;

        return $this;
    }

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(string $statut): static
    {
        $this->statut = $statut;

        return $this;
    }

    public function getPlace(): ?Place
    {
        return $this->place;
    }

    public function setPlace(?Place $place): static
    {
        $this->place = $place;

        return $this;
    }

    public function getShape(): ?Shape
    {
        return $this->shape;
    }

    public function setShape(?Shape $shape): static
    {
        $this->shape = $shape;

        return $this;
    }

    public function getCampus(): ?Campus
    {
        return $this->campus;
    }

    public function setCampus(?Campus $campus): static
    {
        $this->campus = $campus;

        return $this;
    }

    public function getOrganizer(): ?User
    {
        return $this->organizer;
    }

    public function setOrganizer(?User $organizer): static
    {
        $this->organizer = $organizer;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getParticipant(): Collection
    {
        return $this->participant;
    }

    public function addParticipant(User $participant): static
    {
        if (!$this->participant->contains($participant)) {
            $this->participant->add($participant);
        }

        return $this;
    }

    public function removeParticipant(User $participant): static
    {
        $this->participant->removeElement($participant);

        return $this;
    }
}
