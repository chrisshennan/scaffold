<?php

declare(strict_types=1);

namespace Scaffold\CoreBundle\Entity;

use App\Entity\Author;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Scaffold\CoreBundle\Traits\TimestampableTrait;

#[ORM\MappedSuperclass]
class BlogContent extends Content
{
    use TimestampableTrait;

    #[ORM\Column(length: 255)]
    private string $slug;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $mainImage = null;

    #[ORM\Column(nullable: true)]
    private ?DateTimeImmutable $publishedAt = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $canonicalUrl = null;

    #[ORM\ManyToOne(targetEntity: Author::class, inversedBy: 'blogPosts')]
    private Author $author;

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }

    public function getMainImage(): ?string
    {
        return $this->mainImage;
    }

    public function setMainImage(?string $mainImage): static
    {
        $this->mainImage = $mainImage;

        return $this;
    }

    public function getPublishedAt(): ?DateTimeImmutable
    {
        return $this->publishedAt;
    }

    public function setPublishedAt(?DateTimeImmutable $publishedAt): static
    {
        $this->publishedAt = $publishedAt;

        return $this;
    }

    public function isPublished(): bool
    {
        if ($this->publishedAt === null) {
            return false;
        }

        return $this->getPublishedAt() < new DateTimeImmutable();
    }

    public function getCanonicalUrl(): ?string
    {
        return $this->canonicalUrl;
    }

    public function setCanonicalUrl(?string $canonicalUrl): static
    {
        $this->canonicalUrl = $canonicalUrl;

        return $this;
    }

    public function getAuthor(): Author
    {
        return $this->author;
    }

    public function setAuthor(Author $author): static
    {
        $this->author = $author;

        return $this;
    }
}
