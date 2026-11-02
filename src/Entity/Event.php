<?php

namespace App\Entity;

use App\Repository\EventRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;

// Une session / date du sound system
#[ORM\Entity(repositoryClass: EventRepository::class)]
class Event
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['api_event_index'])]
    private ?int $id = null;

    #[ORM\Column(length: 150)]
    #[Assert\NotBlank(message: 'Il faut un titre.')]
    #[Assert\Length(max: 150)]
    #[Groups(['api_event_index'])]
    private ?string $title = null;

    #[ORM\Column]
    #[Assert\NotNull(message: 'Il faut une date.')]
    #[Groups(['api_event_index'])]
    private ?\DateTimeImmutable $date = null;

    #[ORM\Column(length: 150)]
    #[Assert\NotBlank(message: 'Il faut un lieu.')]
    #[Assert\Length(max: 150)]
    #[Groups(['api_event_index'])]
    private ?string $place = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank(message: 'Il faut une description.')]
    #[Groups(['api_event_index'])]
    private ?string $description = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getDate(): ?\DateTimeImmutable
    {
        return $this->date;
    }

    public function setDate(?\DateTimeImmutable $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getPlace(): ?string
    {
        return $this->place;
    }

    public function setPlace(?string $place): static
    {
        $this->place = $place;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }
}
