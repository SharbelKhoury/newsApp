<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups; // Import the Groups annotation

#[ORM\Entity(repositoryClass: CategoryRepository::class)]
class Category
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['category:read'])] // Add serialization group
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['category:read'])] // Add serialization group
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['category:read'])] // Add serialization group
    private ?string $description = null;

    #[ORM\OneToMany(targetEntity: News::class, mappedBy: 'category')]
    private $news;

    /**
     * @return mixed
     */
    #[Groups(['category:read'])] // Add serialization group
    public function getNews()
    {
        return $this->news;
    }

    /**
     * @param mixed $news
     */
    public function setNews($news): void
    {
        $this->news = $news;
    }

    #[Groups(['category:read'])] // Add serialization group
    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    #[Groups(['category:read'])] // Add serialization group
    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    #[Groups(['category:read'])] // Add serialization group
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
