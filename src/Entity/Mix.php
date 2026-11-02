<?php

namespace App\Entity;

use App\Repository\MixRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;

// Un mix ou une dubplate à écouter
#[ORM\Entity(repositoryClass: MixRepository::class)]
class Mix
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['api_mix_index'])]
    private ?int $id = null;

    #[ORM\Column(length: 150)]
    #[Assert\NotBlank(message: 'Il faut un titre.')]
    #[Assert\Length(max: 150)]
    #[Groups(['api_mix_index'])]
    private ?string $title = null;

    #[ORM\Column(length: 100)]
    #[Assert\NotBlank(message: 'Il faut un nom de selecta.')]
    #[Assert\Length(max: 100)]
    #[Groups(['api_mix_index'])]
    private ?string $artist = null;

    #[ORM\Column(length: 30)]
    #[Assert\NotBlank(message: 'Il faut une durée.')]
    #[Assert\Length(max: 30)]
    #[Groups(['api_mix_index'])]
    private ?string $duration = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Length(max: 255)]
    #[Groups(['api_mix_index'])]
    private ?string $url = null;

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

    public function getArtist(): ?string
    {
        return $this->artist;
    }

    public function setArtist(?string $artist): static
    {
        $this->artist = $artist;

        return $this;
    }

    public function getDuration(): ?string
    {
        return $this->duration;
    }

    public function setDuration(?string $duration): static
    {
        $this->duration = $duration;

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(?string $url): static
    {
        $this->url = $url;

        return $this;
    }
}
