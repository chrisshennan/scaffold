<?php

declare(strict_types=1);

namespace Scaffold\CoreBundle\Entity;

use App\Entity\BlogContent;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\UuidV7;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\MappedSuperclass]
abstract class Author
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    private UuidV7 $id;

    #[ORM\Column(type: 'string', length: 255)]
    private string $name;

    #[ORM\Column(type: 'string', length: 1024)]
    private string $biography;

    #[ORM\Column(type: 'string', length: 255)]
    private string $email;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private string $jobTitle;

    #[Assert\Url(protocols: ['https'])]
    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private string $website;

    #[ORM\OneToMany(targetEntity: BlogContent::class, mappedBy: 'author', cascade: ['persist', 'remove'])]
    private Collection $blogPosts;

    public function __construct()
    {
        $this->id = new UuidV7();
        $this->blogPosts = new ArrayCollection();
    }

    public function getId(): UuidV7
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getBiography(): string
    {
        return $this->biography;
    }

    public function setBiography(string $biography): static
    {
        $this->biography = $biography;

        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getBlogPosts(): Collection
    {
        return $this->blogPosts;
    }

    public function addBlogPost(BlogContent $blogPost): static
    {
        if (!$this->blogPosts->contains($blogPost)) {
            $this->blogPosts->add($blogPost);
            $blogPost->setAuthor($this);
        }

        return $this;
    }

    public function removeBlogPost(BlogContent $blogPost): static
    {
        $this->blogPosts->removeElement($blogPost);

        return $this;
    }

    public function __toString(): string
    {
        return $this->getName().' ('.$this->getEmail().')';
    }

    public function getJobTitle(): string
    {
        return $this->jobTitle;
    }

    public function setJobTitle(string $jobTitle): static
    {
        $this->jobTitle = $jobTitle;

        return $this;
    }

    public function getWebsite(): string
    {
        return $this->website;
    }

    public function setWebsite(string $website): static
    {
        $this->website = $website;

        return $this;
    }
}
