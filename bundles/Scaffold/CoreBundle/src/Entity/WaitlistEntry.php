<?php

declare(strict_types=1);

namespace Scaffold\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Scaffold\CoreBundle\Repository\WaitlistEntryRepository;
use Scaffold\CoreBundle\Traits\TimestampableTrait;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Uid\UuidV7;

#[ORM\Entity(repositoryClass: WaitlistEntryRepository::class)]
class WaitlistEntry
{
    use TimestampableTrait;

    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    private UuidV7 $id;

    #[ORM\Column(length: 255)]
    private string $email;

    #[ORM\Column]
    private string $source;

    public function __construct()
    {
        $this->id = new UuidV7();
    }

    public function getId(): ?Uuid
    {
        return $this->id;
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

    public function getSource(): string
    {
        return $this->source;
    }

    public function setSource(string $source): static
    {
        $this->source = $source;

        return $this;
    }
}
