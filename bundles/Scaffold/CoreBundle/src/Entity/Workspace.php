<?php

declare(strict_types=1);

namespace Scaffold\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Override;
use Scaffold\CoreBundle\Traits\TimestampableTrait;
use Stringable;
use Symfony\Component\Uid\UuidV7;

#[ORM\MappedSuperclass]
class Workspace implements Stringable
{
    use TimestampableTrait;

    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    private UuidV7 $id;

    #[ORM\Column(length: 255)]
    private string $name;

    public function __construct()
    {
        $this->id = new UuidV7();
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

    #[Override]
    public function __toString(): string
    {
        return $this->getName();
    }
}
