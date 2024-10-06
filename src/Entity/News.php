<?php

namespace App\Entity;

use App\Repository\NewsRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use App\Entity\User;
use App\Entity\Tag;
use App\Entity\Category;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: NewsRepository::class)]
class News
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['news:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['news:read'])]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups(['news:read'])]
    private ?string $description = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['news:read'])]
    private ?array $images = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups(['news:read'])]
    private ?\DateTimeInterface $customDate = null;

    #[ORM\ManyToOne(targetEntity: Category::class, inversedBy: 'news')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['news:read'])]
    private ?Category $category = null;

    #[ORM\ManyToMany(targetEntity: Tag::class, inversedBy: 'news')]
    #[ORM\JoinTable(name: "news_tags")]
    #[Groups(['news:read'])]
    private Collection $tags;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['news:read'])]
    private ?User $author = null;

    public function __construct()
    {
        $this->tags = new ArrayCollection();
    }

    #[Groups(['news:read'])]
    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;
        return $this;
    }

    #[Groups(['news:read'])]
    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;
        return $this;
    }

    #[Groups(['news:read'])]
    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;
        return $this;
    }

    #[Groups(['news:read'])]
    public function getImages(): ?array
    {
        return $this->images;
    }

    public function setImages(?array $images): static
    {
        $this->images = $images;
        return $this;
    }

    #[Groups(['news:read'])]
    public function getCustomDate(): ?\DateTimeInterface
    {
        return $this->customDate;
    }

    public function setCustomDate(\DateTimeInterface $customDate): static
    {
        $this->customDate = $customDate;
        return $this;
    }

    #[Groups(['news:read'])]
    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(Category $category): static
    {
        $this->category = $category;
        return $this;
    }

    #[Groups(['news:read'])]
    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function addTag(Tag $tag): static
    {
        if (!$this->tags->contains($tag)) {
            $this->tags[] = $tag;
        }
        return $this;
    }

    public function removeTag(Tag $tag): static
    {
        $this->tags->removeElement($tag);
        return $this;
    }

    #[Groups(['news:read'])]
    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(User $author): static
    {
        $this->author = $author;
        return $this;
    }
}
