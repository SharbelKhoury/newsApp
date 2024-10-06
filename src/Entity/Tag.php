<?php

namespace App\Entity;

use App\Repository\TagRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: TagRepository::class)]
class Tag
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['tag:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['tag:read'])]
    private ?string $title = null;

    #[ORM\ManyToMany(targetEntity: News::class, mappedBy: 'tags')]
    private $news;

    /**
     * @return mixed
     */
    #[Groups(['tag:read'])]
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

    #[Groups(['tag:read'])]
    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function __toString()
    {
        return $this->title;
    }


    #[Groups(['tag:read'])]
    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }
}
