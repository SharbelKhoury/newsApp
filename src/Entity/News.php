<?php

namespace App\Entity;

use App\Repository\NewsRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\User;
use App\Entity\Tag;
use App\Entity\Category;
use Symfony\Component\Serializer\Annotation\Groups; // Import the Groups annotation

#[ORM\Entity(repositoryClass: NewsRepository::class)]
class News
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['news:read'])] // Add serialization group
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['news:read'])] // Add serialization group
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups(['news:read'])] // Add serialization group
    private ?string $description = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['news:read'])] // Add serialization group
    private ?array $images = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups(['news:read'])] // Add serialization group
    private ?\DateTimeInterface $customDate = null;

    #[ORM\ManyToOne(targetEntity: Category::class, inversedBy: 'news')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['news:read'])] // Add serialization group
    private $category;

    #[ORM\ManyToMany(targetEntity: Tag::class, inversedBy: 'news')]
    #[Groups(['news:read'])] // Add serialization group
    private $tags;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['news:read'])] // Add serialization group
    private $author;

    /**
     * @return mixed
     */
    #[Groups(['news:read'])] // Add serialization group
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param mixed $category
     */
    public function setCategory($category): void
    {
        $this->category = $category;
    }

    /**
     * @return mixed
     */
    #[Groups(['news:read'])] // Add serialization group
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * @param mixed $tags
     */
    public function setTags($tags): void
    {
        $this->tags = $tags;
    }

    /**
     * @return mixed
     */
    #[Groups(['news:read'])] // Add serialization group
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * @param mixed $author
     */
    public function setAuthor($author): void
    {
        $this->author = $author;
    }

    #[Groups(['news:read'])] // Add serialization group
    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    #[Groups(['news:read'])] // Add serialization group
    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    #[Groups(['news:read'])] // Add serialization group
    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    #[Groups(['news:read'])] // Add serialization group
    public function getImages(): ?array
    {
        return $this->images;
    }

    public function setImages(?array $images): static
    {
        $this->images = $images;

        return $this;
    }

    #[Groups(['news:read'])] // Add serialization group
    public function getCustomDate(): ?\DateTimeInterface
    {
        return $this->customDate;
    }

    public function setCustomDate(\DateTimeInterface $customDate): static
    {
        $this->customDate = $customDate;

        return $this;
    }
}
