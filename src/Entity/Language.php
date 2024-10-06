<?php

namespace App\Entity;

use App\Repository\LanguageRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups; // Import the Groups annotation

#[ORM\Entity(repositoryClass: LanguageRepository::class)]
class Language
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['language:read'])] // Add serialization group
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['language:read'])] // Add serialization group
    private ?string $title = null;

    #[ORM\Column(length: 10)]
    #[Groups(['language:read'])] // Add serialization group
    private ?string $locale = null;

    #[Groups(['language:read'])] // Add serialization group
    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    #[Groups(['language:read'])] // Add serialization group
    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    #[Groups(['language:read'])] // Add serialization group
    public function getLocale(): ?string
    {
        return $this->locale;
    }

    public function setLocale(string $locale): static
    {
        $this->locale = $locale;

        return $this;
    }
}
